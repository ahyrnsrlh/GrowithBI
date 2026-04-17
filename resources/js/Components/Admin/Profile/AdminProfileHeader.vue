<template>
    <div
        class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg overflow-hidden"
    >
        <div class="px-6 py-8 text-white">
            <div class="flex items-center space-x-6">
                <div class="relative">
                    <input
                        ref="photoInput"
                        type="file"
                        class="hidden"
                        accept="image/*"
                        @change="$emit('photo-selected', $event)"
                    />

                    <div
                        v-if="photoPreview || authUser.profile_photo_path"
                        class="w-24 h-24 rounded-full overflow-hidden border-4 border-white border-opacity-20"
                    >
                        <img
                            :src="
                                photoPreview ||
                                `/storage/${authUser.profile_photo_path}`
                            "
                            :alt="authUser.name"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <div
                        v-else
                        class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-3xl font-bold border-4 border-white border-opacity-20"
                    >
                        {{ getInitials(authUser.name) }}
                    </div>

                    <button
                        class="absolute bottom-0 right-0 w-8 h-8 bg-white text-blue-600 rounded-full flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors"
                        title="Ubah foto profile"
                        @click="photoInput?.click()"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </button>

                    <button
                        v-if="photoPreview || authUser.profile_photo_path"
                        class="absolute top-0 right-0 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors"
                        title="Hapus foto"
                        @click="$emit('remove-photo')"
                    >
                        <svg
                            class="w-3 h-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div class="flex-1">
                    <h1 class="text-2xl font-bold mb-2">{{ authUser.name }}</h1>
                    <p class="text-blue-100 mb-1">{{ authUser.email }}</p>
                    <div
                        class="flex items-center space-x-4 text-sm text-blue-100"
                    >
                        <span class="flex items-center">
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
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                            Administrator
                        </span>
                        <span class="flex items-center">
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
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            Bergabung {{ formatDate(authUser.created_at) }}
                        </span>
                    </div>
                </div>

                <div class="text-right">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                    >
                        <span
                            class="w-2 h-2 bg-green-400 rounded-full mr-2"
                        ></span>
                        Online
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const photoInput = ref(null);

defineProps({
    authUser: {
        type: Object,
        required: true,
    },
    photoPreview: {
        type: String,
        default: null,
    },
    getInitials: {
        type: Function,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
});

defineEmits(["photo-selected", "remove-photo"]);
</script>
