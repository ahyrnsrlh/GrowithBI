<template>
    <Head title="Pendaftaran Magang" />

    <GuestLayout>
        <div
            class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12"
        >
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Pendaftaran Magang
                    </h1>
                    <p class="text-lg text-gray-600">
                        Bergabunglah dengan program magang GrowithBI dan
                        kembangkan karir Anda!
                    </p>
                </div>

                <!-- Application Form -->
                <div class="bg-white shadow-xl rounded-lg p-8">
                    <form
                        @submit.prevent="submit"
                        enctype="multipart/form-data"
                    >
                        <!-- Personal Information -->
                        <div class="mb-8">
                            <h2
                                class="text-xl font-semibold text-gray-900 mb-4"
                            >
                                Informasi Pribadi
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel
                                        for="name"
                                        value="Nama Lengkap"
                                    />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        required
                                        autofocus
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.name"
                                    />
                                </div>

                                <div>
                                    <InputLabel for="email" value="Email" />
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="form.email"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.email"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="phone"
                                        value="Nomor Telepon"
                                    />
                                    <TextInput
                                        id="phone"
                                        type="tel"
                                        class="mt-1 block w-full"
                                        v-model="form.phone"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.phone"
                                    />
                                </div>

                                <div>
                                    <InputLabel for="address" value="Alamat" />
                                    <textarea
                                        id="address"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        rows="3"
                                        v-model="form.address"
                                        required
                                    ></textarea>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.address"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="mb-8">
                            <h2
                                class="text-xl font-semibold text-gray-900 mb-4"
                            >
                                Informasi Akademik
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel
                                        for="university"
                                        value="Universitas/Institusi"
                                    />
                                    <TextInput
                                        id="university"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.university"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.university"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="major"
                                        value="Jurusan/Program Studi"
                                    />
                                    <TextInput
                                        id="major"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.major"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.major"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="semester"
                                        value="Semester"
                                    />
                                    <select
                                        id="semester"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.semester"
                                        required
                                    >
                                        <option value="">Pilih Semester</option>
                                        <option
                                            v-for="i in 14"
                                            :key="i"
                                            :value="i"
                                        >
                                            Semester {{ i }}
                                        </option>
                                    </select>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.semester"
                                    />
                                </div>

                                <div>
                                    <InputLabel for="gpa" value="IPK/GPA" />
                                    <TextInput
                                        id="gpa"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="4"
                                        class="mt-1 block w-full"
                                        v-model="form.gpa"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.gpa"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Internship Details -->
                        <div class="mb-8">
                            <h2
                                class="text-xl font-semibold text-gray-900 mb-4"
                            >
                                Detail Magang
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel
                                        for="division_id"
                                        value="Divisi yang Diminati"
                                    />
                                    <select
                                        id="division_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.division_id"
                                        required
                                    >
                                        <option value="">Pilih Divisi</option>
                                        <option
                                            v-for="division in divisions"
                                            :key="division.id"
                                            :value="division.id"
                                        >
                                            {{ division.name }}
                                        </option>
                                    </select>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.division_id"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="start_date"
                                        value="Tanggal Mulai Magang"
                                    />
                                    <TextInput
                                        id="start_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.start_date"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.start_date"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="end_date"
                                        value="Tanggal Selesai Magang"
                                    />
                                    <TextInput
                                        id="end_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.end_date"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.end_date"
                                    />
                                </div>
                            </div>

                            <div class="mt-6">
                                <InputLabel
                                    for="motivation"
                                    value="Motivasi dan Alasan Melamar"
                                />
                                <textarea
                                    id="motivation"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="4"
                                    v-model="form.motivation"
                                    placeholder="Jelaskan motivasi Anda mengikuti program magang di GrowithBI..."
                                    required
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.motivation"
                                />
                            </div>
                        </div>

                        <!-- Document Upload -->
                        <div class="mb-8">
                            <h2
                                class="text-xl font-semibold text-gray-900 mb-4"
                            >
                                Upload Dokumen
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel
                                        for="cv"
                                        value="CV (PDF, maksimal 5MB)"
                                    />
                                    <input
                                        id="cv"
                                        type="file"
                                        accept=".pdf"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        @input="
                                            form.cv = $event.target.files[0]
                                        "
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.cv"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="transcript"
                                        value="Transkrip Nilai (PDF, maksimal 5MB)"
                                    />
                                    <input
                                        id="transcript"
                                        type="file"
                                        accept=".pdf"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        @input="
                                            form.transcript =
                                                $event.target.files[0]
                                        "
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.transcript"
                                    />
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel
                                        for="recommendation_letter"
                                        value="Surat Rekomendasi (Opsional, PDF, maksimal 5MB)"
                                    />
                                    <input
                                        id="recommendation_letter"
                                        type="file"
                                        accept=".pdf"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        @input="
                                            form.recommendation_letter =
                                                $event.target.files[0]
                                        "
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="
                                            form.errors.recommendation_letter
                                        "
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <PrimaryButton
                                class="ms-4"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{
                                    form.processing
                                        ? "Mengirim..."
                                        : "Kirim Pendaftaran"
                                }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600 mb-2">
                        Sudah mendaftar?
                        <Link
                            :href="route('public.check.status')"
                            class="text-blue-600 hover:text-blue-800 font-medium"
                        >
                            Cek status pendaftaran Anda
                        </Link>
                    </p>
                    <p class="text-gray-600">
                        <Link
                            :href="route('home')"
                            class="text-blue-600 hover:text-blue-800 font-medium"
                        >
                            ← Kembali ke halaman utama
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    divisions: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    name: "",
    email: "",
    phone: "",
    address: "",
    university: "",
    major: "",
    semester: "",
    gpa: "",
    division_id: "",
    start_date: "",
    end_date: "",
    motivation: "",
    cv: null,
    transcript: null,
    recommendation_letter: null,
});

const submit = () => {
    form.post(route("public.apply.submit"));
};
</script>
