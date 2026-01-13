<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, onMounted, computed, watch } from "vue";

const page = usePage();

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "peserta",
    phone: "",
    address: "",
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);
const showSuccessModal = ref(false);

// Check for registration success from session
const registrationSuccess = computed(
    () => page.props.flash?.registration_success
);
const userEmail = computed(() => page.props.flash?.user_email);
const userName = computed(() => page.props.flash?.user_name);

// Watch for flash messages
watch(
    () => page.props.flash,
    (newFlash) => {
        if (newFlash?.registration_success) {
            showSuccessModal.value = true;

            // Auto redirect after 3 seconds
            setTimeout(() => {
                window.location.href = route("login");
            }, 3000);
        }
    },
    { immediate: true }
);

onMounted(() => {
    if (registrationSuccess.value) {
        showSuccessModal.value = true;

        // Auto redirect after 3 seconds
        setTimeout(() => {
            window.location.href = route("login");
        }, 3000);
    }
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => {
            if (!form.hasErrors) {
                form.reset("password", "password_confirmation");
            }
        },
    });
};

const closeModalAndRedirect = () => {
    showSuccessModal.value = false;
    window.location.href = route("login");
};
</script>

<template>
    <Head title="Daftar - GrowithBI" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <!-- Register Card -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Logo and Header -->
                <div class="text-center mb-8">
                    <Link href="/" class="inline-flex justify-center mb-4">
                        <img
                            src="/storage/logo_web2.png"
                            alt="GrowithBI Logo"
                            class="h-12 w-auto object-contain"
                        />
                    </Link>
                    <p class="text-gray-600 text-sm">
                        Program Magang Bank Indonesia Provinsi Lampung
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Name and Email Row -->
                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Name Field -->
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
                            <div
                                v-if="form.errors.name"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Email Field -->
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
                            <div
                                v-if="form.errors.email"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>
                    </div>

                    <!-- Phone Field -->
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
                        <div
                            v-if="form.errors.phone"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.phone }}
                        </div>
                    </div>

                    <!-- Address Field -->
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
                        <div
                            v-if="form.errors.address"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.address }}
                        </div>
                    </div>

                    <!-- Password Fields Row -->
                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Password Field -->
                        <div>
                            <label
                                for="password"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Password *
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                    class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl leading-5 bg-white/50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Minimal 8 karakter"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg
                                        v-if="!showPassword"
                                        class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M10 12a2 2 0 100-4 2 2 0 000 4z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                            clip-rule="evenodd"
                                        />
                                        <path
                                            d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div
                                v-if="form.errors.password"
                                class="mt-2 text-sm text-red-600 flex items-center"
                            >
                                <svg
                                    class="w-4 h-4 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                {{ form.errors.password }}
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label
                                for="password_confirmation"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Konfirmasi Password *
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <input
                                    id="password_confirmation"
                                    :type="
                                        showPasswordConfirmation
                                            ? 'text'
                                            : 'password'
                                    "
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl leading-5 bg-white/50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Ulangi password"
                                />
                                <button
                                    type="button"
                                    @click="
                                        showPasswordConfirmation =
                                            !showPasswordConfirmation
                                    "
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg
                                        v-if="!showPasswordConfirmation"
                                        class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M10 12a2 2 0 100-4 2 2 0 000 4z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                            clip-rule="evenodd"
                                        />
                                        <path
                                            d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div
                                v-if="form.errors.password_confirmation"
                                class="mt-2 text-sm text-red-600 flex items-center"
                            >
                                <svg
                                    class="w-4 h-4 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                {{ form.errors.password_confirmation }}
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                    >
                        <svg
                            v-if="form.processing"
                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        {{
                            form.processing
                                ? "Processing..."
                                : "Create an account"
                        }}
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <Link
                            :href="route('login')"
                            class="font-medium text-blue-600 hover:text-blue-700"
                        >
                            Sign in
                        </Link>
                    </p>
                </div>
            </div>
        </div>

        <!-- Success Modal - Modern & Minimalist -->
        <div
            v-if="showSuccessModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        >
            <div
                class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 transform transition-all animate-[fadeInScale_0.3s_ease-out] relative overflow-hidden"
            >
                <!-- Background gradient -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-green-50 opacity-50"
                ></div>

                <!-- Content -->
                <div class="relative z-10">
                    <!-- Success Icon -->
                    <div class="text-center mb-6">
                        <div
                            class="mx-auto w-20 h-20 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mb-4 shadow-lg"
                        >
                            <svg
                                class="w-10 h-10 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="3"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            Berhasil! 🎉
                        </h3>

                        <p class="text-gray-600 text-sm leading-relaxed">
                            Akun Anda telah dibuat
                        </p>
                    </div>

                    <!-- User Info Card -->
                    <div
                        class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-4 mb-6 shadow-sm"
                    >
                        <div class="text-center">
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1"
                            >
                                Email Terdaftar
                            </p>
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ userEmail }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button
                        @click="closeModalAndRedirect"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-2xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Lanjut ke Login
                    </button>

                    <!-- Auto redirect info -->
                    <p
                        class="text-xs text-gray-400 text-center mt-4 flex items-center justify-center"
                    >
                        <svg
                            class="w-3 h-3 mr-1"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Otomatis dialihkan dalam 3 detik
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fadeInScale {
    0% {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
