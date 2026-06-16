/**
 * useAttendanceRealtime
 *
 * A reusable composable that subscribes to the 'attendance-channel' via
 * Laravel Echo and synchronises a reactive attendance list in real-time.
 *
 * Architecture decisions
 * ─────────────────────
 * 1. Single responsibility: this composable ONLY handles WebSocket state.
 *    Filtering, sorting, and pagination remain in useAdminAttendanceIndexPage.
 *
 * 2. The composable owns a LOCAL reactive copy of the first page of
 *    attendance records (attendanceList).  It is initialised from the
 *    Inertia prop (server-side paginated data) so the page renders
 *    instantly without a blank flash.
 *
 * 3. On AttendanceCreated:
 *      - Duplicate guard by ID prevents the same row appearing twice
 *        if the user somehow receives the event twice (network blip, etc.)
 *      - The new record is PREPENDED (unshift) to match the server order
 *        (orderBy date DESC, created_at DESC).
 *      - totalCount is incremented by +1.
 *
 * 4. On AttendanceUpdated:
 *      - Only the affected row is patched in-place using Object.assign.
 *      - Vue's reactivity system re-renders only that single <tr>.
 *      - No full list re-render occurs.
 *
 * 5. Echo is initialised lazily (waits for window.Echo to be available)
 *    using a requestIdleCallback-style delay that matches bootstrap.js.
 *
 * 6. Channel is properly left in onUnmounted to avoid memory leaks.
 */

import { onMounted, onUnmounted, ref } from 'vue';

const CHANNEL_NAME = 'attendance-channel';

export function useAttendanceRealtime(initialAttendances) {
    // ─── State ───────────────────────────────────────────────────────────────

    /**
     * Reactive clone of attendances.data (first page records).
     * Mutated directly by WebSocket events — never triggers Inertia reload.
     */
    const attendanceList = ref(
        Array.isArray(initialAttendances?.data)
            ? [...initialAttendances.data]
            : [],
    );

    /**
     * Total record count shown in the table header badge.
     * Starts from the server-provided total and is incremented on new records.
     */
    const totalCount = ref(initialAttendances?.total ?? 0);

    /** Whether the WebSocket channel is currently connected. */
    const isConnected = ref(false);

    /** ID of the most recently inserted/updated attendance row (for highlight). */
    const recentlyUpdatedId = ref(null);

    // ─── Helpers ─────────────────────────────────────────────────────────────

    const clearHighlight = () => {
        setTimeout(() => {
            recentlyUpdatedId.value = null;
        }, 2500);
    };

    // ─── Event handlers ──────────────────────────────────────────────────────

    /**
     * Handle AttendanceCreated — insert a brand-new row at position 0.
     */
    const handleAttendanceCreated = (event) => {
        const incoming = event.attendance;
        if (!incoming?.id) return;

        // Duplicate guard: don't insert if the ID already exists in the list.
        const alreadyExists = attendanceList.value.some(
            (a) => a.id === incoming.id,
        );
        if (alreadyExists) return;

        // Prepend — server sorts DESC so newest record is first.
        attendanceList.value.unshift(incoming);
        totalCount.value += 1;

        recentlyUpdatedId.value = incoming.id;
        clearHighlight();

        if (import.meta.env.DEV) {
            console.debug('[Realtime] AttendanceCreated', incoming.id);
        }
    };

    /**
     * Handle AttendanceUpdated — patch only the changed row in-place.
     */
    const handleAttendanceUpdated = (event) => {
        const incoming = event.attendance;
        if (!incoming?.id) return;

        const index = attendanceList.value.findIndex(
            (a) => a.id === incoming.id,
        );

        if (index !== -1) {
            // Patch existing row — Vue tracks the ref array and re-renders only this row.
            Object.assign(attendanceList.value[index], incoming);
        }
        // If the row is not on the current page (pagination), we silently skip.
        // The user will see the updated data when they navigate to that page.

        recentlyUpdatedId.value = incoming.id;
        clearHighlight();

        if (import.meta.env.DEV) {
            console.debug('[Realtime] AttendanceUpdated', incoming.id);
        }
    };

    // ─── Channel subscription ─────────────────────────────────────────────────

    let echoChannel = null;

    const subscribeToChannel = () => {
        if (!window.Echo) {
            console.warn('[Realtime] Laravel Echo not available. Real-time updates are disabled.');
            return;
        }

        echoChannel = window.Echo.channel(CHANNEL_NAME)
            .listen('.attendance.created', handleAttendanceCreated)
            .listen('.attendance.updated', handleAttendanceUpdated);

        isConnected.value = true;

        if (import.meta.env.DEV) {
            console.debug('[Realtime] Subscribed to', CHANNEL_NAME);
        }
    };

    const unsubscribeFromChannel = () => {
        if (window.Echo && echoChannel) {
            window.Echo.leave(CHANNEL_NAME);
            echoChannel = null;
            isConnected.value = false;

            if (import.meta.env.DEV) {
                console.debug('[Realtime] Left channel', CHANNEL_NAME);
            }
        }
    };

    // ─── Sync when Inertia reloads (filter change, pagination, etc.) ─────────

    /**
     * When the parent page navigates (filter applied, page changed),
     * the Inertia prop `attendances` changes. Call this to re-sync the
     * local reactive state from the new prop value so the table stays
     * consistent with the server response while still receiving WebSocket
     * updates on top of that.
     */
    const syncFromProp = (attendancesProp) => {
        attendanceList.value = Array.isArray(attendancesProp?.data)
            ? [...attendancesProp.data]
            : [];
        totalCount.value = attendancesProp?.total ?? 0;
    };

    // ─── Lifecycle ────────────────────────────────────────────────────────────

    onMounted(() => {
        // Defer until Echo is initialised (mirrors bootstrap.js runWhenIdle).
        if (window.Echo) {
            subscribeToChannel();
        } else {
            // Echo is loaded lazily; wait for it.
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
                    console.warn('[Realtime] Echo did not initialise within', maxWait, 'ms.');
                }
            }, interval);
        }
    });

    onUnmounted(() => {
        unsubscribeFromChannel();
    });

    // ─── Public API ───────────────────────────────────────────────────────────

    return {
        attendanceList,
        totalCount,
        isConnected,
        recentlyUpdatedId,
        syncFromProp,
    };
}
