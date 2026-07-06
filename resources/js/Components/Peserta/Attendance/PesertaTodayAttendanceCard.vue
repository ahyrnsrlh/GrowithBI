<template>
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
        <div class="bg-[#2F4C9E] px-6 py-4 border-b border-[#274089]">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Status Hari Ini
            </h2>
            <!-- Face enrolled indicator -->
            <div class="mt-2 flex items-center gap-1.5">
                <div
                    class="w-2 h-2 rounded-full"
                    :class="faceEnrolled ? 'bg-emerald-400' : 'bg-amber-400 animate-pulse'"
                />
                <span class="text-xs font-medium" :class="faceEnrolled ? 'text-emerald-300' : 'text-amber-300'">
                    {{ faceEnrolled ? 'Wajah terdaftar ✓' : 'Wajah belum terdaftar' }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <PesertaTodayAttendanceOverview
                :todayAttendance="todayAttendance"
                :formatTime="formatTime"
            />

            <PesertaTodayAttendanceActions
                :todayAttendance="todayAttendance"
                :isProcessing="isProcessing"
                :actionType="actionType"
                :isWithinCheckInTime="isWithinCheckInTime"
                :isWithinCheckOutTime="isWithinCheckOutTime"
                :faceEnrolled="faceEnrolled"
                @check-in="$emit('check-in')"
                @check-out="$emit('check-out')"
            />
        </div>
    </div>
</template>

<script setup>
import PesertaTodayAttendanceOverview from "@/Components/Peserta/Attendance/PesertaTodayAttendanceOverview.vue";
import PesertaTodayAttendanceActions from "@/Components/Peserta/Attendance/PesertaTodayAttendanceActions.vue";

defineProps({
    todayAttendance:      { type: Object,   default: null },
    isProcessing:         { type: Boolean,  default: false },
    actionType:           { type: String,   default: "" },
    isWithinCheckInTime:  { type: Boolean,  default: false },
    isWithinCheckOutTime: { type: Boolean,  default: false },
    formatTime:           { type: Function, required: true },
    faceEnrolled:         { type: Boolean,  default: true },
});

defineEmits(["check-in", "check-out"]);
</script>
