<template>
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">
                Daftar Peserta Magang
            </h3>
            <p class="text-sm text-gray-600 mt-1">
                Kelola dan pantau data peserta magang
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Peserta
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Kontak
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Divisi
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status Aplikasi
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status Akun
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Tanggal Daftar
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
                        v-for="participant in participantsData"
                        :key="participant.id"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div
                                        class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center"
                                    >
                                        <span
                                            class="text-white font-semibold text-lg"
                                        >
                                            {{
                                                participant.name
                                                    ?.charAt(0)
                                                    ?.toUpperCase() || "U"
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ participant.name || "-" }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ participant.email || "-" }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ participant.phone || "-" }}
                            </div>
                            <div
                                class="text-sm text-gray-500 max-w-32 truncate"
                            >
                                {{ participant.address || "-" }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div
                                v-if="getAcceptedApplication(participant)"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                            >
                                {{
                                    getAcceptedApplication(participant).division
                                        .name
                                }}
                            </div>
                            <div v-else class="text-sm text-gray-500">
                                Belum ditempatkan
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div v-if="getAcceptedApplication(participant)">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Diterima
                                </span>
                            </div>
                            <div
                                v-else-if="
                                    participant.applications &&
                                    participant.applications.length > 0
                                "
                            >
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    :class="
                                        getLastApplicationStatusClass(
                                            participant,
                                        )
                                    "
                                >
                                    {{
                                        getLastApplicationStatusText(
                                            participant,
                                        )
                                    }}
                                </span>
                            </div>
                            <div v-else>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800"
                                >
                                    Belum mendaftar
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <button
                                @click="$emit('toggle-status', participant)"
                                :class="[
                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors',
                                    participant.is_active
                                        ? 'bg-green-100 text-green-800 hover:bg-green-200'
                                        : 'bg-red-100 text-red-800 hover:bg-red-200',
                                ]"
                            >
                                <div
                                    class="w-2 h-2 rounded-full mr-2"
                                    :class="
                                        participant.is_active
                                            ? 'bg-green-500'
                                            : 'bg-red-500'
                                    "
                                ></div>
                                {{
                                    participant.is_active
                                        ? "Aktif"
                                        : "Tidak Aktif"
                                }}
                            </button>
                        </td>

                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(participant.created_at) }}
                        </td>

                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                        >
                            <Link
                                v-if="participant.id"
                                :href="
                                    safeRoute(
                                        'admin.participants.show',
                                        participant.id,
                                    )
                                "
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
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
                                Detail
                            </Link>
                            <button
                                v-else
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed"
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
                                Detail
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="participantsData.length === 0" class="text-center py-12">
            <div class="mx-auto h-24 w-24 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900">
                Tidak ada peserta ditemukan
            </h3>
            <p class="mt-2 text-gray-500">
                Belum ada peserta yang terdaftar atau sesuai dengan filter yang
                dipilih.
            </p>
        </div>

        <div
            v-if="participantsData.length > 0"
            class="px-6 py-4 border-t border-gray-200 bg-gray-50"
        >
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ participants?.from || 0 }} sampai
                    {{ participants?.to || 0 }} dari
                    {{ participants?.total || 0 }} hasil
                </div>
                <div class="flex space-x-1">
                    <Link
                        v-for="link in participants?.links || []"
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'px-4 py-2 text-sm rounded-lg border transition-colors',
                            link.active
                                ? 'bg-blue-600 text-white border-blue-600'
                                : link.url
                                  ? 'text-gray-700 border-gray-300 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50'
                                  : 'text-gray-400 border-gray-200 cursor-not-allowed',
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    participants: {
        type: Object,
        required: true,
    },
    participantsData: {
        type: Array,
        default: () => [],
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getAcceptedApplication: {
        type: Function,
        required: true,
    },
    getLastApplicationStatusClass: {
        type: Function,
        required: true,
    },
    getLastApplicationStatusText: {
        type: Function,
        required: true,
    },
    safeRoute: {
        type: Function,
        required: true,
    },
});

defineEmits(["toggle-status"]);
</script>
