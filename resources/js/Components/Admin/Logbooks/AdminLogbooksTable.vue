<template>
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        Daftar Logbook
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Menampilkan {{ paginatedLogbooks.length }} dari
                        {{ filteredLogbooks.length }} logbook
                    </p>
                </div>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        :checked="isAllSelected"
                        @change="$emit('toggle-select-all')"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">Pilih Semua</span>
                </label>
            </div>
        </div>

        <div v-if="filteredLogbooks.length > 0" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            <input
                                type="checkbox"
                                :checked="isAllSelected"
                                @change="$emit('toggle-select-all')"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            />
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Peserta & Tanggal
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Judul & Aktivitas
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Duration
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="logbook in paginatedLogbooks"
                        :key="logbook.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input
                                type="checkbox"
                                :value="logbook.id"
                                v-model="selectedModel"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-sm font-semibold text-blue-600"
                                    >
                                        {{
                                            logbook.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ logbook.user.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ formatDate(logbook.date) }}
                                    </div>
                                    <div
                                        v-if="logbook.user.division"
                                        class="text-xs text-gray-400"
                                    >
                                        {{ logbook.user.division.name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <div
                                    class="text-sm font-medium text-gray-900 truncate"
                                >
                                    {{ logbook.title }}
                                </div>
                                <div class="text-sm text-gray-500 line-clamp-2">
                                    {{ logbook.activities }}
                                </div>
                                <div
                                    v-if="
                                        logbook.attachments &&
                                        logbook.attachments.length > 0
                                    "
                                    class="flex items-center mt-1"
                                >
                                    <svg
                                        class="w-3 h-3 mr-1 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                        />
                                    </svg>
                                    <span class="text-xs text-gray-400"
                                        >{{
                                            logbook.attachments.length
                                        }}
                                        file</span
                                    >
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 mr-1 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <span class="text-sm text-gray-900"
                                    >{{ logbook.duration || 0 }} jam</span
                                >
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="getStatusClass(logbook.status)"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            >
                                {{ getStatusText(logbook.status) }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                        >
                            <div class="flex space-x-2">
                                <Link
                                    :href="
                                        route('admin.logbooks.show', logbook.id)
                                    "
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-md hover:bg-blue-100 transition-colors"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    Review
                                </Link>

                                <button
                                    v-if="logbook.status === 'submitted'"
                                    @click="$emit('quick-approve', logbook.id)"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 text-sm font-medium rounded-md hover:bg-green-100 transition-colors"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    Setujui
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="filteredLogbooks.length > 0"
            class="px-6 py-4 border-t border-gray-200 bg-gray-50"
        >
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-4"
            >
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-700">Tampilkan:</span>
                    <select
                        :value="itemsPerPage"
                        @change="
                            $emit(
                                'change-items-per-page',
                                Number($event.target.value),
                            )
                        "
                        class="rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option :value="5">5</option>
                        <option :value="10">10</option>
                        <option :value="25">25</option>
                        <option :value="50">50</option>
                    </select>
                    <span class="text-sm text-gray-700">data</span>
                </div>

                <div class="text-sm text-gray-700">
                    Halaman {{ currentPage }} dari {{ totalPages }}
                    <span class="text-gray-500"
                        >({{ filteredLogbooks.length }} total)</span
                    >
                </div>

                <div class="flex items-center gap-1">
                    <button
                        @click="$emit('go-to-page', currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 rounded-md text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200 text-gray-700"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>

                    <template
                        v-for="(page, index) in paginationRange"
                        :key="index"
                    >
                        <button
                            v-if="page !== '...'"
                            @click="$emit('go-to-page', page)"
                            :class="[
                                'px-3 py-1 rounded-md text-sm font-medium transition-colors',
                                currentPage === page
                                    ? 'bg-blue-600 text-white'
                                    : 'hover:bg-gray-200 text-gray-700',
                            ]"
                        >
                            {{ page }}
                        </button>
                        <span v-else class="px-2 text-gray-500">...</span>
                    </template>

                    <button
                        @click="$emit('go-to-page', currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-1 rounded-md text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200 text-gray-700"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-else class="p-12 text-center">
            <div class="w-24 h-24 mx-auto mb-4 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                    />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Tidak ada logbook ditemukan
            </h3>
            <p class="text-gray-500">
                Coba ubah kriteria pencarian atau filter yang Anda gunakan
            </p>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    filteredLogbooks: {
        type: Array,
        default: () => [],
    },
    paginatedLogbooks: {
        type: Array,
        default: () => [],
    },
    selectedLogbooks: {
        type: Array,
        default: () => [],
    },
    isAllSelected: {
        type: Boolean,
        default: false,
    },
    currentPage: {
        type: Number,
        default: 1,
    },
    totalPages: {
        type: Number,
        default: 1,
    },
    itemsPerPage: {
        type: Number,
        default: 10,
    },
    paginationRange: {
        type: Array,
        default: () => [],
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getStatusClass: {
        type: Function,
        required: true,
    },
    getStatusText: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits([
    "toggle-select-all",
    "update:selectedLogbooks",
    "quick-approve",
    "go-to-page",
    "change-items-per-page",
]);

const selectedModel = computed({
    get: () => props.selectedLogbooks,
    set: (value) => emit("update:selectedLogbooks", value),
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
