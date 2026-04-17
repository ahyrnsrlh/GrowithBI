import { computed, onUnmounted, reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";

export function useAdminAttendanceIndexPage(props) {
    const isLoading = ref(false);
    const showFilters = ref(true);
    const showPhotoModal = ref(false);
    const selectedPhoto = ref(null);
    const selectedPhotoTitle = ref("");

    const filterForm = reactive({
        search: props.filters?.search || "",
        date_from: props.filters?.date_from || "",
        date_to: props.filters?.date_to || "",
        division_id: props.filters?.division_id || "",
        participant_id: props.filters?.participant_id || "",
        status: props.filters?.status || "",
    });

    const activeFiltersCount = computed(
        () => Object.values(filterForm).filter((value) => value !== "").length,
    );

    const paginationLinks = computed(() => props.attendances?.links || []);

    const openPhotoModal = (photoUrl, title) => {
        selectedPhoto.value = photoUrl;
        selectedPhotoTitle.value = title;
        showPhotoModal.value = true;
    };

    const closePhotoModal = () => {
        showPhotoModal.value = false;
        selectedPhoto.value = null;
        selectedPhotoTitle.value = "";
    };

    const applyFilters = () => {
        isLoading.value = true;
        router.get(route("admin.attendance.index"), filterForm, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
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
            filterForm[key] = "";
        });
        applyFilters();
    };

    const exportData = () => {
        window.location.href = route("admin.attendance.export", filterForm);
    };

    onUnmounted(() => {
        debounceSearch.cancel();
    });

    return {
        isLoading,
        showFilters,
        showPhotoModal,
        selectedPhoto,
        selectedPhotoTitle,
        filterForm,
        activeFiltersCount,
        paginationLinks,
        openPhotoModal,
        closePhotoModal,
        applyFilters,
        debounceSearch,
        clearFilters,
        exportData,
    };
}
