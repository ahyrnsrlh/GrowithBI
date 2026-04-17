import { computed, onMounted, onUnmounted, ref } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// Fix Leaflet default markers
// eslint-disable-next-line no-underscore-dangle
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
    iconUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
    shadowUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
});

export function useAdminMapsPage(props) {
    const map = ref(null);
    const markers = ref([]);
    const isLoading = ref(true);
    const isRefreshing = ref(false);
    const attendances = ref(props.attendances || []);
    const stats = ref(props.stats || {});

    let refreshInterval = null;

    const formattedCurrentDate = computed(() => {
        return new Date(props.currentDate).toLocaleDateString("id-ID", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    });

    const getStatusBadgeClass = (status) => {
        const classes = {
            "On-Time":
                "bg-emerald-50 text-emerald-700 border border-emerald-200",
            Late: "bg-amber-50 text-amber-700 border border-amber-200",
            Absent: "bg-rose-50 text-rose-700 border border-rose-200",
            "Not-Checked-Out":
                "bg-blue-50 text-blue-700 border border-blue-200",
        };

        return (
            classes[status] || "bg-gray-50 text-gray-700 border border-gray-200"
        );
    };

    const getStatusDotClass = (status) => {
        const dotClasses = {
            "On-Time": "bg-emerald-500",
            Late: "bg-amber-500",
            Absent: "bg-rose-500",
            "Not-Checked-Out": "bg-blue-500",
        };

        return dotClasses[status] || "bg-gray-500";
    };

    const getStatusText = (status) => {
        const statusMap = {
            "On-Time": "Tepat Waktu",
            Late: "Terlambat",
            Absent: "Tidak Hadir",
            "Not-Checked-Out": "Belum Check-out",
        };

        return statusMap[status] || status;
    };

    const getInitials = (name) => {
        if (!name) {
            return "?";
        }

        return name
            .split(" ")
            .map((n) => n[0])
            .join("")
            .toUpperCase()
            .slice(0, 2);
    };

    const addAttendanceMarkers = () => {
        if (!map.value) {
            return;
        }

        markers.value.forEach((marker) => map.value.removeLayer(marker));
        markers.value = [];

        attendances.value.forEach((attendance) => {
            const markerColor = attendance.is_valid_location
                ? "#10b981"
                : "#ef4444";

            const attendanceIcon = L.divIcon({
                className: "custom-div-icon",
                html: `
                    <div class="w-6 h-6 rounded-full border-2 border-white shadow-lg flex items-center justify-center" style="background-color: ${markerColor}">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                    </div>
                `,
                iconSize: [24, 24],
                iconAnchor: [12, 12],
            });

            const marker = L.marker(
                [attendance.latitude, attendance.longitude],
                {
                    icon: attendanceIcon,
                },
            ).addTo(map.value).bindPopup(`
                    <div class="min-w-48">
                        <h3 class="font-semibold text-gray-900 mb-2">${attendance.user_name}</h3>
                        <div class="space-y-1 text-sm">
                            <p><span class="font-medium">Divisi:</span> ${attendance.division}</p>
                            <p><span class="font-medium">Check-in:</span> ${attendance.check_in_time}</p>
                            <p><span class="font-medium">Status:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusBadgeClass(attendance.status)}">
                                    ${getStatusText(attendance.status)}
                                </span>
                            </p>
                            <p><span class="font-medium">Lokasi:</span>
                                <span class="font-medium ${attendance.is_valid_location ? "text-emerald-600" : "text-rose-600"}">
                                    ${attendance.is_valid_location ? "Valid" : "Tidak Valid"}
                                </span>
                            </p>
                        </div>
                    </div>
                `);

            markers.value.push(marker);
        });
    };

    const initializeMap = () => {
        map.value = L.map("map").setView(
            [props.officeLocation.latitude, props.officeLocation.longitude],
            15,
        );

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: "© OpenStreetMap contributors",
        }).addTo(map.value);

        const officeIcon = L.divIcon({
            className: "custom-div-icon",
            html: `
                <div class="w-6 h-6 bg-blue-600 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                    <div class="w-2 h-2 bg-white rounded-full"></div>
                </div>
            `,
            iconSize: [24, 24],
            iconAnchor: [12, 12],
        });

        L.marker(
            [props.officeLocation.latitude, props.officeLocation.longitude],
            {
                icon: officeIcon,
            },
        ).addTo(map.value).bindPopup(`
                <div class="text-center">
                    <h3 class="font-semibold text-gray-900">${props.officeLocation.name}</h3>
                    <p class="text-sm text-gray-600">Kantor Pusat</p>
                    <p class="text-xs text-gray-500 mt-1">Radius Valid: ${props.officeLocation.radius}m</p>
                </div>
            `);

        L.circle(
            [props.officeLocation.latitude, props.officeLocation.longitude],
            {
                radius: props.officeLocation.radius,
                color: "#3b82f6",
                fillColor: "#3b82f6",
                fillOpacity: 0.1,
                weight: 2,
                dashArray: "5, 5",
            },
        ).addTo(map.value);

        addAttendanceMarkers();
        isLoading.value = false;
    };

    const refreshData = async () => {
        isRefreshing.value = true;

        try {
            const response = await fetch(
                route("admin.attendance.locations.api"),
                {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                },
            );

            const data = await response.json();

            if (data.success) {
                attendances.value = data.data;
                stats.value = data.stats;
                addAttendanceMarkers();
            }
        } catch (error) {
            console.error("Error refreshing data:", error);
        } finally {
            isRefreshing.value = false;
        }
    };

    const showNotification = (attendance) => {
        console.log(
            `New attendance: ${attendance.user_name} - ${attendance.status}`,
        );
    };

    onMounted(() => {
        setTimeout(initializeMap, 100);

        refreshInterval = setInterval(refreshData, 30000);

        if (window.Echo) {
            window.Echo.channel("admin-maps").listen(
                "AttendanceUpdated",
                (e) => {
                    const existingIndex = attendances.value.findIndex(
                        (a) => a.id === e.attendance.id,
                    );

                    if (existingIndex !== -1) {
                        attendances.value[existingIndex] = e.attendance;
                    } else {
                        attendances.value.push(e.attendance);
                    }

                    stats.value = e.stats;
                    addAttendanceMarkers();
                    showNotification(e.attendance);
                },
            );
        } else {
            console.log("WebSocket not available, using polling method");
        }
    });

    onUnmounted(() => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }

        if (window.Echo) {
            window.Echo.leave("admin-maps");
        }

        if (map.value) {
            map.value.remove();
        }
    });

    return {
        isLoading,
        isRefreshing,
        attendances,
        stats,
        formattedCurrentDate,
        refreshData,
        getStatusBadgeClass,
        getStatusDotClass,
        getStatusText,
        getInitials,
    };
}
