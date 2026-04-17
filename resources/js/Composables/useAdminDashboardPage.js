import { computed } from "vue";

export function useAdminDashboardPage(props) {
    const currentDate = computed(() => {
        const options = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };

        return new Date().toLocaleDateString("id-ID", options);
    });

    const acceptanceRate = computed(() => {
        const total = props.stats?.total_applications || 0;
        const accepted = props.stats?.accepted_applications || 0;
        return total > 0 ? Math.round((accepted / total) * 100) : 0;
    });

    const rejectionRate = computed(() => {
        const total = props.stats?.total_applications || 0;
        const rejected = props.stats?.rejected_applications || 0;
        return total > 0 ? Math.round((rejected / total) * 100) : 0;
    });

    const statusChartData = computed(() => {
        if (
            !props.statusDistribution ||
            props.statusDistribution.length === 0
        ) {
            return {
                labels: ["Tidak ada data"],
                datasets: [
                    {
                        data: [1],
                        backgroundColor: ["#E5E7EB"],
                        borderWidth: 2,
                        borderColor: "#ffffff",
                    },
                ],
            };
        }

        return {
            labels: props.statusDistribution.map((item) => item.name),
            datasets: [
                {
                    data: props.statusDistribution.map((item) => item.value),
                    backgroundColor: ["#10B981", "#F59E0B", "#EF4444"],
                    borderWidth: 3,
                    borderColor: "#ffffff",
                    hoverBorderWidth: 4,
                    hoverBorderColor: "#ffffff",
                },
            ],
        };
    });

    const trendsChartData = computed(() => {
        const trends = props.applicationTrends || [];

        return {
            labels: trends.map((t) => t.month),
            datasets: [
                {
                    label: "Pendaftaran",
                    data: trends.map((t) => t.applications),
                    borderColor: "#3B82F6",
                    backgroundColor: "rgba(59, 130, 246, 0.1)",
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: "#3B82F6",
                    pointBorderColor: "#ffffff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
            ],
        };
    });

    const divisionChartData = computed(() => {
        const divisions = props.divisionData || [];

        return {
            labels: divisions.map((d) => d.name),
            datasets: [
                {
                    label: "Diterima",
                    data: divisions.map((d) => d.accepted),
                    backgroundColor: "#10B981",
                    borderColor: "#10B981",
                    borderWidth: 0,
                    borderRadius: 6,
                },
                {
                    label: "Kuota",
                    data: divisions.map((d) => d.quota),
                    backgroundColor: "#E5E7EB",
                    borderColor: "#D1D5DB",
                    borderWidth: 0,
                    borderRadius: 6,
                },
            ],
        };
    });

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

    const getStatusText = (status) => {
        const statusMap = {
            approved: "Disetujui",
            diterima: "Diterima",
            pending: "Menunggu",
            menunggu: "Menunggu",
            rejected: "Ditolak",
            ditolak: "Ditolak",
            draft: "Draft",
        };

        return statusMap[status] || status;
    };

    const getStatusClasses = (status) => {
        const classMap = {
            approved:
                "bg-emerald-50 text-emerald-700 border border-emerald-200",
            diterima:
                "bg-emerald-50 text-emerald-700 border border-emerald-200",
            pending: "bg-amber-50 text-amber-700 border border-amber-200",
            menunggu: "bg-amber-50 text-amber-700 border border-amber-200",
            rejected: "bg-red-50 text-red-700 border border-red-200",
            ditolak: "bg-red-50 text-red-700 border border-red-200",
            draft: "bg-gray-50 text-gray-700 border border-gray-200",
        };

        return (
            classMap[status] ||
            "bg-gray-50 text-gray-700 border border-gray-200"
        );
    };

    const getStatusDotClass = (status) => {
        const dotMap = {
            approved: "bg-emerald-500",
            diterima: "bg-emerald-500",
            pending: "bg-amber-500",
            menunggu: "bg-amber-500",
            rejected: "bg-red-500",
            ditolak: "bg-red-500",
            draft: "bg-gray-500",
        };

        return dotMap[status] || "bg-gray-500";
    };

    const formatDate = (dateString) => {
        if (!dateString) {
            return "-";
        }

        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
        });
    };

    return {
        currentDate,
        acceptanceRate,
        rejectionRate,
        statusChartData,
        trendsChartData,
        divisionChartData,
        getInitials,
        getStatusText,
        getStatusClasses,
        getStatusDotClass,
        formatDate,
    };
}
