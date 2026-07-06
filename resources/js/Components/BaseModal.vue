<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 overflow-y-auto"
                @click.self="$emit('close')"
            >
                <div
                    class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center"
                >
                    <div
                        class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                        @click="$emit('close')"
                    ></div>
                    <Transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="show"
                            :class="[
                                'relative bg-white rounded-2xl w-full shadow-xl overflow-hidden my-8 align-middle inline-block text-left transition-all transform',
                                maxWidth,
                            ]"
                        >
                            <!-- Header -->
                            <div
                                class="flex items-center justify-between p-6 border-b border-gray-100"
                            >
                                <h3 class="text-xl font-bold text-gray-900">
                                    <slot name="title">{{ title }}</slot>
                                </h3>
                                <button
                                    @click="$emit('close')"
                                    class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 rounded-lg hover:bg-gray-50"
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
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <div class="p-6 bg-gray-50/50">
                                <slot></slot>
                            </div>

                            <!-- Footer -->
                            <div
                                v-if="$slots.footer"
                                class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3"
                            >
                                <slot name="footer"></slot>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { onMounted, onUnmounted } from "vue";

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: "" },
    maxWidth: { type: String, default: "max-w-4xl" },
});

const emit = defineEmits(["close"]);

const handleKeydown = (event) => {
    if (event.key === "Escape" && props.show) {
        emit("close");
    }
};

onMounted(() => {
    window.addEventListener("keydown", handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeydown);
});
</script>
