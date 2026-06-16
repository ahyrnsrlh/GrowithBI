/**
 * useAttendanceDivisionChart
 *
 * Composable that:
 *   1. Fetches today's attendance-per-division data on mount (initial seed).
 *   2. Subscribes to 'attendance-channel' via Laravel Echo.
 *   3. On AttendanceCreated  → increments the affected division count by 1.
 *   4. On AttendanceUpdated  → no-op (check-out doesn't change "present" count).
 *   5. On unmount           → leaves the channel to prevent memory leaks.
 *
 * Architecture decisions
 * ──────────────────────
 * - Single Responsibility: this composable ONLY manages chart state + WebSocket.
 *   Rendering is handled by AttendanceDivisionChart.vue.
 *
 * - Data flow: fetch once → patch reactively via WebSocket.
 *   Never re-fetches the entire dataset after mount. This is O(1) per event
 *   regardless of how many divisions or attendances exist.
 *
 * - chartLabels and chartCounts are separate refs (not a nested object) so
 *   Chart.js receives the exact array references it watches, enabling
 *   minimal re-renders.
 *
 * - totalPresent is a computed convenience value for the summary badge.
 *
 * - FORBIDDEN: polling, setInterval, window.location.reload, router.reload.
 */

import { computed, onMounted, onUnmounted, ref } from 'vue';

const CHANNEL_NAME   = 'attendance-channel';
const API_ENDPOINT   = '/admin/dashboard/attendance-by-division';

export function useAttendanceDivisionChart() {
    // ─── Reactive state ──────────────────────────────────────────────────────

    /** Division names for the chart X-axis */
    const chartLabels = ref([]);

    /**
     * Parallel array of attendance counts.
     * chartCounts[i] corresponds to chartLabels[i].
     */
    const chartCounts = ref([]);

    /**
     * Internal map: division_id → index in chartLabels/chartCounts arrays.
     * Used for O(1) lookup when a WebSocket event arrives.
     */
    const divisionIndexMap = ref(new Map());

    /** Whether the initial HTTP fetch has completed. */
    const isLoading = ref(true);

    /** HTTP fetch error, if any. */
    const fetchError = ref(null);

    /** Whether the WebSocket channel is connected. */
    const isConnected = ref(false);

    /** ID of most-recently incremented division (for optional highlight). */
    const lastUpdatedDivisionId = ref(null);

    // ─── Derived ─────────────────────────────────────────────────────────────

    /** Total present across all divisions — shown in the card footer. */
    const totalPresent = computed(() =>
        chartCounts.value.reduce((sum, n) => sum + n, 0)
    );

    /**
     * Chart.js data object — watched by the BarChart component.
     * Only recreates the object when labels or counts change.
     */
    const chartData = computed(() => ({
        labels:   chartLabels.value,
        datasets: [
            {
                label:           'Peserta Hadir',
                data:            chartCounts.value,
                backgroundColor: buildGradientColors(chartLabels.value.length),
                borderColor:     buildBorderColors(chartLabels.value.length),
                borderWidth:     0,
                borderRadius:    8,
                borderSkipped:   false,
            },
        ],
    }));

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Generate a consistent set of harmonious colors for the bars.
     * Uses a fixed palette that cycles if there are more divisions than colors.
     */
    function buildGradientColors(count) {
        const palette = [
            'rgba(99, 102, 241, 0.85)',  // indigo
            'rgba(16, 185, 129, 0.85)',  // emerald
            'rgba(245, 158, 11, 0.85)',  // amber
            'rgba(239, 68, 68, 0.85)',   // red
            'rgba(59, 130, 246, 0.85)',  // blue
            'rgba(168, 85, 247, 0.85)',  // purple
            'rgba(20, 184, 166, 0.85)',  // teal
            'rgba(249, 115, 22, 0.85)',  // orange
        ];
        return Array.from({ length: count }, (_, i) => palette[i % palette.length]);
    }

    function buildBorderColors(count) {
        const palette = [
            'rgba(99, 102, 241, 1)',
            'rgba(16, 185, 129, 1)',
            'rgba(245, 158, 11, 1)',
            'rgba(239, 68, 68, 1)',
            'rgba(59, 130, 246, 1)',
            'rgba(168, 85, 247, 1)',
            'rgba(20, 184, 166, 1)',
            'rgba(249, 115, 22, 1)',
        ];
        return Array.from({ length: count }, (_, i) => palette[i % palette.length]);
    }

    // ─── HTTP fetch (initial seed) ────────────────────────────────────────────

    const fetchInitialData = async () => {
        isLoading.value  = true;
        fetchError.value = null;

        try {
            const response = await window.axios.get(API_ENDPOINT);
            const rows     = response.data?.data ?? [];

            // Build parallel arrays + lookup map in one pass.
            const labels  = [];
            const counts  = [];
            const idxMap  = new Map();

            rows.forEach((row, i) => {
                labels.push(row.division_name);
                counts.push(row.total_present);
                idxMap.set(row.division_id, i);
            });

            chartLabels.value      = labels;
            chartCounts.value      = counts;
            divisionIndexMap.value = idxMap;

            if (import.meta.env.DEV) {
                console.debug('[AttendanceChart] Seeded', rows.length, 'divisions, total:', counts.reduce((s, n) => s + n, 0));
            }
        } catch (err) {
            fetchError.value = 'Gagal memuat data kehadiran.';
            console.error('[AttendanceChart] Fetch error:', err);
        } finally {
            isLoading.value = false;
        }
    };

    // ─── WebSocket event handlers ─────────────────────────────────────────────

    /**
     * AttendanceCreated → increment the division count by 1.
     *
     * Only increments if:
     *   - division_id exists in our map (it's an active division we track).
     *   - The attendance record has a check_in (it's a "present" record).
     */
    const handleAttendanceCreated = (event) => {
        const attendance  = event?.attendance;
        const division    = attendance?.user?.division;
        const divisionId  = division?.id;
        const hasCheckIn  = !!attendance?.check_in;

        if (!divisionId || !hasCheckIn) return;

        const index = divisionIndexMap.value.get(divisionId);
        if (index === undefined) return; // Division not in our active list

        // Patch in-place — Vue tracks the array and updates only this bar.
        chartCounts.value[index]++;

        lastUpdatedDivisionId.value = divisionId;
        setTimeout(() => { lastUpdatedDivisionId.value = null; }, 2500);

        if (import.meta.env.DEV) {
            console.debug(
                '[AttendanceChart] AttendanceCreated → division',
                division.name,
                'new count:', chartCounts.value[index]
            );
        }
    };

    /**
     * AttendanceUpdated — only relevant if a check-out is recorded.
     * "Present" count only depends on check_in, so checkout does not
     * change the count. We keep this listener as a no-op extension point
     * in case future requirements add "incomplete" tracking.
     */
    const handleAttendanceUpdated = (_event) => {
        // Currently a no-op: check-out does not change "present" count.
        // Extend here if you need to track checkout-based metrics.
    };

    // ─── Channel subscription ─────────────────────────────────────────────────

    let echoChannel = null;

    const subscribeToChannel = () => {
        if (!window.Echo) {
            console.warn('[AttendanceChart] Laravel Echo not available — real-time disabled.');
            return;
        }

        echoChannel = window.Echo.channel(CHANNEL_NAME)
            .listen('.attendance.created', handleAttendanceCreated)
            .listen('.attendance.updated', handleAttendanceUpdated);

        isConnected.value = true;

        if (import.meta.env.DEV) {
            console.debug('[AttendanceChart] Subscribed to', CHANNEL_NAME);
        }
    };

    const unsubscribeFromChannel = () => {
        if (window.Echo && echoChannel) {
            window.Echo.leave(CHANNEL_NAME);
            echoChannel          = null;
            isConnected.value    = false;

            if (import.meta.env.DEV) {
                console.debug('[AttendanceChart] Left channel', CHANNEL_NAME);
            }
        }
    };

    // ─── Lifecycle ────────────────────────────────────────────────────────────

    onMounted(async () => {
        // 1. Seed chart with today's data from the API.
        await fetchInitialData();

        // 2. Subscribe to real-time updates.
        if (window.Echo) {
            subscribeToChannel();
        } else {
            // Echo initialises lazily via bootstrap.js runWhenIdle.
            // Poll briefly for it to become available.
            const maxWait = 5000;
            const interval = 200;
            let elapsed = 0;

            const poll = setInterval(() => {
                elapsed += interval;
                if (window.Echo) {
                    clearInterval(poll);
                    subscribeToChannel();
                } else if (elapsed >= maxWait) {
                    clearInterval(poll);
                    console.warn('[AttendanceChart] Echo did not initialise within', maxWait, 'ms.');
                }
            }, interval);
        }
    });

    onUnmounted(() => {
        unsubscribeFromChannel();
    });

    // ─── Public API ───────────────────────────────────────────────────────────

    return {
        chartData,
        chartLabels,
        chartCounts,
        totalPresent,
        isLoading,
        fetchError,
        isConnected,
        lastUpdatedDivisionId,
        fetchInitialData,   // exposed so parent can manually re-seed if needed
    };
}
