<template>
    <div
        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8"
    >
        <div
            class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50"
        >
            <h2
                class="text-lg font-semibold text-gray-900 flex items-center gap-2"
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
                Status Hari Ini
            </h2>
        </div>

        <div class="p-6">
            <TodayAttendanceOverviewCards
                :todayAttendance="todayAttendance"
                :formatTime="formatTime"
            />

            <TodayAttendanceActionButtons
                :todayAttendance="todayAttendance"
                :isProcessing="isProcessing"
                :actionType="actionType"
                :isWithinCheckInTime="isWithinCheckInTime"
                :isWithinCheckOutTime="isWithinCheckOutTime"
                @check-in="$emit('check-in')"
                @check-out="$emit('check-out')"
            />
        </div>
    </div>
</template>

<script setup>
import TodayAttendanceOverviewCards from "@/Components/Profile/Attendance/TodayAttendanceOverviewCards.vue";
import TodayAttendanceActionButtons from "@/Components/Profile/Attendance/TodayAttendanceActionButtons.vue";

defineProps({
    todayAttendance: {
        type: Object,
        default: null,
    },
    isProcessing: {
        type: Boolean,
        default: false,
    },
    actionType: {
        type: String,
        default: "",
    },
    isWithinCheckInTime: {
        type: Boolean,
        default: false,
    },
    isWithinCheckOutTime: {
        type: Boolean,
        default: false,
    },
    formatTime: { type: Function, required: true },
});

defineEmits(["check-in", "check-out"]);
</script>
