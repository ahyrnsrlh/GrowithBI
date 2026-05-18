<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label
                    for="name"
                    class="block text-sm font-semibold text-slate-700 mb-2"
                >
                    Nama Lengkap *
                </label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    class="block w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    placeholder="Nama lengkap"
                />
                <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                    {{ form.errors.name }}
                </div>
            </div>

            <div>
                <label
                    for="email"
                    class="block text-sm font-semibold text-slate-700 mb-2"
                >
                    Email Address *
                </label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    class="block w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    placeholder="nama@email.com"
                />
                <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label
                    for="phone"
                    class="block text-sm font-semibold text-slate-700 mb-2"
                >
                    Nomor Telepon
                </label>
                <input
                    id="phone"
                    type="tel"
                    v-model="form.phone"
                    autocomplete="tel"
                    class="block w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    placeholder="081234567890"
                />
                <div v-if="form.errors.phone" class="mt-2 text-sm text-red-600">
                    {{ form.errors.phone }}
                </div>
            </div>

            <div>
                <label
                    for="address"
                    class="block text-sm font-semibold text-slate-700 mb-2"
                >
                    Alamat
                </label>
                <input
                    id="address"
                    v-model="form.address"
                    type="text"
                    class="block w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    placeholder="Alamat lengkap (opsional)"
                />
                <div
                    v-if="form.errors.address"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.address }}
                </div>
            </div>
        </div>
    </div>
</template>
