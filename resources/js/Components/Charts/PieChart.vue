<template>
    <div class="chart-container">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from "vue";
import {
    Chart as ChartJS,
    ArcElement,
    Tooltip,
    Legend,
    CategoryScale,
    LinearScale,
} from "chart.js/auto";

// Register Chart.js components - using auto import for all controllers
ChartJS.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale);

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        default: () => ({}),
    },
});

const chartCanvas = ref(null);
let chartInstance = null;

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                padding: 20,
                usePointStyle: true,
                font: {
                    size: 12,
                },
            },
        },
        tooltip: {
            backgroundColor: "rgba(0, 0, 0, 0.8)",
            titleColor: "white",
            bodyColor: "white",
            borderColor: "rgba(255, 255, 255, 0.1)",
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true,
            callbacks: {
                label: function (context) {
                    const label = context.label || "";
                    const value = context.parsed;
                    const total = context.dataset.data.reduce(
                        (a, b) => a + b,
                        0
                    );
                    const percentage =
                        total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                    return `${label}: ${value} (${percentage}%)`;
                },
            },
        },
    },
};

const createChart = () => {
    console.log("PieChart createChart called");
    console.log("chartCanvas.value:", chartCanvas.value);
    console.log("props.data:", props.data);

    if (!chartCanvas.value) {
        console.log("Canvas element not found");
        return;
    }

    if (!props.data || !props.data.labels || !props.data.datasets) {
        console.log("Missing required data for pie chart creation");
        console.log("props.data:", props.data);
        return;
    }

    // Check if data has valid values
    const hasValidData = props.data.datasets.some(
        (dataset) => dataset.data && dataset.data.some((value) => value > 0)
    );

    if (!hasValidData) {
        console.log("No valid data to display in pie chart");
        return;
    }

    const ctx = chartCanvas.value.getContext("2d");

    // Destroy existing chart
    if (chartInstance) {
        chartInstance.destroy();
    }

    const mergedOptions = {
        ...defaultOptions,
        ...props.options,
    };

    console.log("Creating pie chart with data:", props.data);

    try {
        chartInstance = new ChartJS(ctx, {
            type: "doughnut",
            data: props.data,
            options: mergedOptions,
        });

        console.log("Pie chart created successfully:", chartInstance);
    } catch (error) {
        console.error("Error creating pie chart:", error);
    }
};

onMounted(() => {
    nextTick(() => {
        createChart();
    });
});

watch(
    () => props.data,
    () => {
        createChart();
    },
    { deep: true }
);
</script>

<style scoped>
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}
</style>
