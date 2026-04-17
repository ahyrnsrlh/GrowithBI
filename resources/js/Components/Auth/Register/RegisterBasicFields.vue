<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="space-y-5">
        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label
                    for="name"
                    class="block text-sm font-semibold text-gray-700 mb-2"
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
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    placeholder=""
                />
                <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                    {{ form.errors.name }}
                </div>
            </div>

            <div>
                <label
                    for="email"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Email Address *
                </label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    placeholder=""
                />
                <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </div>
            </div>
        </div>

        <div>
            <label
                for="phone"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Nomor Telepon
            </label>
            <input
                id="phone"
                type="tel"
                v-model="form.phone"
                autocomplete="tel"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                placeholder="081234567890"
            />
            <div v-if="form.errors.phone" class="mt-2 text-sm text-red-600">
                {{ form.errors.phone }}
            </div>
        </div>

        <div>
            <label
                for="address"
                class="block text-sm font-semibold text-gray-700 mb-2"
            >
                Alamat
            </label>
            <textarea
                id="address"
                v-model="form.address"
                rows="3"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                placeholder="Alamat lengkap Anda (opsional)"
            ></textarea>
            <div v-if="form.errors.address" class="mt-2 text-sm text-red-600">
                {{ form.errors.address }}
            </div>
        </div>
    </div>
</template>
