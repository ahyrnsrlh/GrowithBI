<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <div
                class="lg:col-span-3 bg-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
            >
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6"
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
                    </div>
                    <h3 class="font-semibold">Durasi Program</h3>
                </div>
                <div class="h-px bg-white/30 my-4"></div>
                <p class="text-3xl font-bold mb-1">{{ formattedDuration }}</p>
                <p class="text-blue-100 text-sm">Waktu magang</p>
            </div>

            <div
                class="lg:col-span-3 bg-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
            >
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <h3 class="font-semibold">Total Kuota</h3>
                </div>
                <div class="h-px bg-white/30 my-4"></div>
                <p class="text-3xl font-bold mb-1">{{ formatQuota }}</p>
                <p class="text-blue-100 text-sm">Peserta diterima</p>
            </div>

            <div
                class="lg:col-span-3 bg-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
            >
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            />
                        </svg>
                    </div>
                    <h3 class="font-semibold">Slot Tersedia</h3>
                </div>
                <div class="h-px bg-white/30 my-4"></div>
                <p class="text-3xl font-bold mb-1">
                    {{ formatAvailableSlots }}
                </p>
                <p
                    :class="
                        availableSlots > 0 ? 'text-blue-100' : 'text-red-200'
                    "
                    class="text-sm"
                >
                    {{ availableSlots > 0 ? "Masih tersedia" : "Kuota penuh" }}
                </p>
            </div>

            <div
                class="lg:col-span-3 bg-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
            >
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <h3 class="font-semibold">Deadline</h3>
                </div>
                <div class="h-px bg-white/30 my-4"></div>
                <p class="text-2xl font-bold mb-1">
                    {{ formatDate(division.application_deadline) }}
                </p>
                <p class="text-blue-100 text-sm">Batas pendaftaran</p>
            </div>

            <div
                class="lg:col-span-8 bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
            >
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">
                        Deskripsi Program
                    </h2>
                </div>
                <p
                    v-if="division.description"
                    class="text-gray-700 leading-relaxed whitespace-pre-line"
                >
                    {{ division.description }}
                </p>
                <p v-else class="text-gray-400 italic">
                    Deskripsi belum tersedia
                </p>
            </div>

            <div
                class="lg:col-span-4 bg-blue-50 rounded-2xl p-6 shadow-lg border border-blue-100"
            >
                <h3
                    class="font-bold text-gray-900 mb-6 flex items-center gap-2"
                >
                    <svg
                        class="w-5 h-5 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    Jadwal Program
                </h3>
                <div class="space-y-4">
                    <div
                        class="flex items-center gap-3 p-4 bg-white rounded-xl border border-blue-100"
                    >
                        <div
                            class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0"
                        >
                            <svg
                                class="w-5 h-5 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">
                                Mulai Program
                            </p>
                            <p class="font-bold text-gray-900">
                                {{ formatDate(division.start_date) }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 p-4 bg-white rounded-xl border border-green-100"
                    >
                        <div
                            class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0"
                        >
                            <svg
                                class="w-5 h-5 text-green-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">
                                Selesai Program
                            </p>
                            <p class="font-bold text-gray-900">
                                {{ formatDate(division.end_date) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="hasJobDescription"
                class="lg:col-span-6 bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
            >
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">
                        Tugas & Tanggung Jawab
                    </h2>
                </div>
                <ul class="space-y-3">
                    <li
                        v-for="(task, index) in normalizedJobDescription"
                        :key="index"
                        class="flex items-start gap-3"
                    >
                        <span
                            class="flex-shrink-0 w-7 h-7 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center text-xs font-bold"
                        >
                            {{ index + 1 }}
                        </span>
                        <span class="text-gray-700 leading-relaxed">{{
                            cleanTaskText(task)
                        }}</span>
                    </li>
                </ul>
            </div>

            <div
                v-if="hasRequirements"
                class="lg:col-span-6 bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
            >
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-amber-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Persyaratan</h2>
                </div>
                <ul class="space-y-3">
                    <li
                        v-for="(req, index) in normalizedRequirements"
                        :key="index"
                        class="flex items-start gap-3"
                    >
                        <svg
                            class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5"
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
                        <span class="text-gray-700 leading-relaxed">{{
                            req
                        }}</span>
                    </li>
                </ul>
            </div>

            <div
                v-if="hasCriteria"
                class="lg:col-span-6 bg-white rounded-2xl p-8 shadow-lg border border-gray-100"
            >
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-purple-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">
                        Kriteria Peserta
                    </h2>
                </div>
                <ul class="space-y-3">
                    <li
                        v-for="(criteria, index) in normalizedCriteria"
                        :key="index"
                        class="flex items-start gap-3"
                    >
                        <svg
                            class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <span class="text-gray-700 leading-relaxed">{{
                            criteria
                        }}</span>
                    </li>
                </ul>
            </div>

            <div
                v-if="hasBenefits"
                class="lg:col-span-6 bg-blue-50 rounded-2xl p-8 shadow-lg border border-blue-100"
            >
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shadow-sm"
                    >
                        <svg
                            class="w-6 h-6 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">
                        Benefit Program
                    </h2>
                </div>
                <ul class="space-y-3">
                    <li
                        v-for="(benefit, index) in normalizedBenefits"
                        :key="index"
                        class="flex items-start gap-3"
                    >
                        <div
                            class="flex-shrink-0 w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                />
                            </svg>
                        </div>
                        <span
                            class="text-gray-700 leading-relaxed font-medium"
                            >{{ benefit }}</span
                        >
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    availableSlots: {
        type: Number,
        required: true,
    },
    cleanTaskText: {
        type: Function,
        required: true,
    },
    division: {
        type: Object,
        required: true,
    },
    formatAvailableSlots: {
        type: String,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    formatQuota: {
        type: String,
        required: true,
    },
    formattedDuration: {
        type: String,
        required: true,
    },
    hasBenefits: {
        type: Boolean,
        default: false,
    },
    hasCriteria: {
        type: Boolean,
        default: false,
    },
    hasJobDescription: {
        type: Boolean,
        default: false,
    },
    hasRequirements: {
        type: Boolean,
        default: false,
    },
    normalizedBenefits: {
        type: Array,
        default: () => [],
    },
    normalizedCriteria: {
        type: Array,
        default: () => [],
    },
    normalizedJobDescription: {
        type: Array,
        default: () => [],
    },
    normalizedRequirements: {
        type: Array,
        default: () => [],
    },
});
</script>
