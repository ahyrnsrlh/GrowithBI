import { ref, computed, onMounted, onUnmounted } from "vue";
import axios from "axios";

export function useAttendanceServerClock() {
    const currentTime = ref("");
    const serverTime = ref({
        timestamp: 0,
        year: 0,
        month: 0,
        day: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
    });
    const lastSyncTime = ref(0);

    const checkInTimeRange = ref({
        check_in: { start: "07:30:00", end: "08:00:00" },
        check_out: { start: "16:00:00", end: "18:00:00" },
    });

    const fetchServerTime = async () => {
        try {
            const response = await axios.get("/api/server-time");
            const serverTimeData = response.data;
            const [year, month, day] = serverTimeData.formatted.date
                .split("-")
                .map(Number);
            const [hours, minutes, seconds] = serverTimeData.formatted.time
                .split(":")
                .map(Number);

            serverTime.value = {
                timestamp: serverTimeData.timestamp,
                year,
                month: month - 1,
                day,
                hours,
                minutes,
                seconds,
            };

            lastSyncTime.value = Date.now();
            updateClockDisplay();
        } catch (error) {
            console.error("Error fetching server time:", error);
            currentTime.value = "Tidak dapat terhubung ke server";
        }
    };

    const fetchCheckInTimeRange = async () => {
        try {
            const response = await axios.get("/api/check-in-time-range");
            checkInTimeRange.value = response.data;
        } catch (error) {
            console.error("Error fetching check-in time range:", error);
        }
    };

    const getServerTime = () => {
        if (!serverTime.value || serverTime.value.timestamp === 0) {
            return null;
        }

        const now = Date.now();
        const elapsedMs = now - lastSyncTime.value;
        const elapsedSeconds = Math.floor(elapsedMs / 1000);

        let seconds = serverTime.value.seconds + elapsedSeconds;
        let minutes = serverTime.value.minutes;
        let hours = serverTime.value.hours;
        let day = serverTime.value.day;

        if (seconds >= 60) {
            minutes += Math.floor(seconds / 60);
            seconds %= 60;
        }

        if (minutes >= 60) {
            hours += Math.floor(minutes / 60);
            minutes %= 60;
        }

        if (hours >= 24) {
            day += Math.floor(hours / 24);
            hours %= 24;
        }

        return {
            hours,
            minutes,
            seconds,
            day,
            month: serverTime.value.month,
            year: serverTime.value.year,
        };
    };

    const updateClockDisplay = () => {
        const now = getServerTime();

        if (!now) {
            currentTime.value = "Memuat waktu server...";
            return;
        }

        const days = [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
        ];
        const months = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        const dateObject = new Date(now.year, now.month, now.day);
        const dayName = days[dateObject.getDay()];
        const monthName = months[now.month];
        const formattedDate = `${dayName}, ${now.day} ${monthName} ${now.year}`;
        const formattedClock = `${String(now.hours).padStart(2, "0")}:${String(
            now.minutes,
        ).padStart(2, "0")}:${String(now.seconds).padStart(2, "0")}`;

        currentTime.value = `${formattedDate} ${formattedClock} WIB`;
    };

    const isWithinCheckInTime = computed(() => {
        const now = getServerTime();
        if (!now) {
            return false;
        }

        const currentMinutes = now.hours * 60 + now.minutes;
        const [startHour, startMinute] = checkInTimeRange.value.check_in.start
            .split(":")
            .map(Number);
        const [endHour, endMinute] = checkInTimeRange.value.check_in.end
            .split(":")
            .map(Number);

        const startMinutes = startHour * 60 + startMinute;
        const endMinutes = endHour * 60 + endMinute;

        return currentMinutes >= startMinutes && currentMinutes <= endMinutes;
    });

    const isWithinCheckOutTime = computed(() => {
        const now = getServerTime();
        if (!now) {
            return false;
        }

        const currentMinutes = now.hours * 60 + now.minutes;
        const [startHour, startMinute] = checkInTimeRange.value.check_out.start
            .split(":")
            .map(Number);
        const [endHour, endMinute] = checkInTimeRange.value.check_out.end
            .split(":")
            .map(Number);

        const startMinutes = startHour * 60 + startMinute;
        const endMinutes = endHour * 60 + endMinute;

        return currentMinutes >= startMinutes && currentMinutes <= endMinutes;
    });

    let clockInterval;
    let serverSyncInterval;

    onMounted(() => {
        fetchServerTime();
        fetchCheckInTimeRange();
        updateClockDisplay();

        clockInterval = setInterval(updateClockDisplay, 1000);
        serverSyncInterval = setInterval(fetchServerTime, 60000);
    });

    onUnmounted(() => {
        if (clockInterval) {
            clearInterval(clockInterval);
        }
        if (serverSyncInterval) {
            clearInterval(serverSyncInterval);
        }
    });

    return {
        currentTime,
        isWithinCheckInTime,
        isWithinCheckOutTime,
    };
}
