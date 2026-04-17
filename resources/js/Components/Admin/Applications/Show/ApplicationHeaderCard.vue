<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <Link
                    href="/admin/applications"
                    class="text-blue-600 hover:text-blue-700"
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
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ application.user?.name || "N/A" }}
                    </h1>
                    <p class="text-gray-600">
                        {{ application.user?.email || "N/A" }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                        getStatusBadgeClass(application.status),
                    ]"
                >
                    {{ application.status }}
                </span>
                <button
                    @click="$emit('open-status-modal')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                >
                    Update Status
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    application: { type: Object, required: true },
    getStatusBadgeClass: { type: Function, required: true },
});

defineEmits(["open-status-modal"]);
</script>
