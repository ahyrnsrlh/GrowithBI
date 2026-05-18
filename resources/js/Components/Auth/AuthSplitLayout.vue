<script setup>
import AuthBrandPanel from "@/Components/Auth/AuthBrandPanel.vue";
</script>

<template>
    <div class="relative min-h-screen bg-white">
        <div
            class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-10"
        >
            <div
                class="grid w-full overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-black/5 lg:grid-cols-[1fr_1.1fr]"
            >
                <div class="p-8 sm:p-10 lg:p-12">
                    <slot />
                </div>
                <div class="hidden lg:block bg-slate-50 p-8">
                    <AuthBrandPanel />
                </div>
            </div>
        </div>
    </div>
</template>
