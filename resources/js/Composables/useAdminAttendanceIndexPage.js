import { computed, onUnmounted, reactive, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { useAttendanceRealtime } from '@/Composables/useAttendanceRealtime';

/**
 * useAdminAttendanceIndexPage
 *
 * Composes two concerns:
 *   1. Filter / pagination / export logic (Inertia-based, unchanged from before).
 *   2. Real-time WebSocket integration via useAttendanceRealtime.
 *
 * The key contract with the template layer:
 *   - `attendanceList`  → reactive array for <tr v-for>  (WebSocket-backed)
 *   - `totalCount`      → reactive integer for the header badge
 *   - `recentlyUpdatedId` → ID of the row to highlight briefly after an update
 *   - `isConnected`     → show/hide the live indicator dot
 *   - All filter/pagination state (unchanged API)
 */
export function useAdminAttendanceIndexPage(props) {
    // ─── Inertia filter state (unchanged) ────────────────────────────────────

    const isLoading = ref(false);
    const showFilters = ref(true);
    const showPhotoModal = ref(false);
    const selectedPhoto = ref(null);
    const selectedPhotoTitle = ref('');

    const filterForm = reactive({
        search:         props.filters?.search         || '',
        date_from:      props.filters?.date_from      || '',
        date_to:        props.filters?.date_to        || '',
        division_id:    props.filters?.division_id    || '',
        participant_id: props.filters?.participant_id || '',
        status:         props.filters?.status         || '',
    });

    const activeFiltersCount = computed(
        () => Object.values(filterForm).filter((v) => v !== '').length,
    );

    const paginationLinks = computed(() => props.attendances?.links || []);

    // ─── Real-time state (WebSocket) ─────────────────────────────────────────

    const {
        attendanceList,
        totalCount,
        isConnected,
        recentlyUpdatedId,
        syncFromProp,
    } = useAttendanceRealtime(props.attendances);

    /**
     * When the Inertia prop changes (filter applied, page navigated),
     * re-sync the local reactive list so it reflects the new server response.
     * This keeps WebSocket state consistent with server state after any reload.
     */
    watch(
        () => props.attendances,
        (newAttendances) => {
            syncFromProp(newAttendances);
        },
        { deep: false },
    );

    // ─── Photo modal ─────────────────────────────────────────────────────────

    const openPhotoModal = (photoUrl, title) => {
        selectedPhoto.value = photoUrl;
        selectedPhotoTitle.value = title;
        showPhotoModal.value = true;
    };

    const closePhotoModal = () => {
        showPhotoModal.value = false;
        selectedPhoto.value = null;
        selectedPhotoTitle.value = '';
    };

    // ─── Filter / pagination actions ─────────────────────────────────────────

    const applyFilters = () => {
        isLoading.value = true;
        router.get(route('admin.attendance.index'), filterForm, {
            preserveState:  true,
            preserveScroll: true,
            replace:        true,
            onFinish: () => {
                isLoading.value = false;
            },
        });
    };

    const debounceSearch = debounce(() => {
        applyFilters();
    }, 400);

    const clearFilters = () => {
        Object.keys(filterForm).forEach((key) => {
            filterForm[key] = '';
        });
        applyFilters();
    };

    const exportData = () => {
        window.location.href = route('admin.attendance.export', filterForm);
    };

    // ─── Cleanup ──────────────────────────────────────────────────────────────

    onUnmounted(() => {
        debounceSearch.cancel();
        // useAttendanceRealtime handles its own cleanup in onUnmounted.
    });

    // ─── Public API ───────────────────────────────────────────────────────────

    return {
        // Filter / UI state
        isLoading,
        showFilters,
        showPhotoModal,
        selectedPhoto,
        selectedPhotoTitle,
        filterForm,
        activeFiltersCount,
        paginationLinks,
        // Actions
        openPhotoModal,
        closePhotoModal,
        applyFilters,
        debounceSearch,
        clearFilters,
        exportData,
        // Real-time state
        attendanceList,
        totalCount,
        isConnected,
        recentlyUpdatedId,
    };
}
