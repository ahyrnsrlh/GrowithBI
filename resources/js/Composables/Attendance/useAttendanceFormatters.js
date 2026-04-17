export function useAttendanceFormatters() {
    const formatDate = (dateString) => {
        if (!dateString) {
            return "-";
        }
        try {
            let date;
            if (
                typeof dateString === "string" &&
                dateString.match(/^\d{4}-\d{2}-\d{2}/)
            ) {
                const parts = dateString.split(/[-\s:]/);
                date = new Date(parts[0], parts[1] - 1, parts[2]);
            } else {
                date = new Date(dateString);
            }

            if (isNaN(date.getTime())) {
                return dateString;
            }

            return date.toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "short",
                year: "numeric",
            });
        } catch (error) {
            console.error("Error formatting date:", error);
            return dateString;
        }
    };

    const formatDayName = (dateString) => {
        if (!dateString) {
            return "-";
        }
        try {
            let date;
            if (
                typeof dateString === "string" &&
                dateString.match(/^\d{4}-\d{2}-\d{2}/)
            ) {
                const parts = dateString.split(/[-\s:]/);
                date = new Date(parts[0], parts[1] - 1, parts[2]);
            } else {
                date = new Date(dateString);
            }

            if (isNaN(date.getTime())) {
                return "";
            }

            return date.toLocaleDateString("id-ID", {
                weekday: "long",
            });
        } catch (error) {
            console.error("Error formatting day name:", error);
            return "";
        }
    };

    const formatTime = (timeString) => {
        if (!timeString) {
            return "-";
        }
        try {
            if (typeof timeString === "string" && timeString.includes(" ")) {
                const timePart = timeString.split(" ")[1];
                if (timePart) {
                    return timePart.substring(0, 5);
                }
            }

            if (
                typeof timeString === "string" &&
                timeString.match(/^\d{2}:\d{2}:\d{2}$/)
            ) {
                return timeString.substring(0, 5);
            }

            const date = new Date(timeString);
            if (isNaN(date.getTime())) {
                return timeString;
            }

            return date.toLocaleTimeString("id-ID", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: false,
                timeZone: "Asia/Jakarta",
            });
        } catch (error) {
            console.error("Error formatting time:", error, timeString);
            return timeString;
        }
    };

    return {
        formatDate,
        formatDayName,
        formatTime,
    };
}
