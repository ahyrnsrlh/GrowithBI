<template>
    <div class="chart-container">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
} from 'chart.js/auto'

// Register Chart.js components - using auto import for all controllers
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
)

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        default: () => ({})
    }
})

const chartCanvas = ref(null)
let chartInstance = null

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        intersect: false,
    },
    scales: {
        x: {
            display: true,
            grid: {
                display: false
            },
            ticks: {
                font: {
                    size: 12
                }
            }
        },
        y: {
            display: true,
            grid: {
                color: 'rgba(0, 0, 0, 0.1)'
            },
            ticks: {
                font: {
                    size: 12
                },
                beginAtZero: true
            }
        }
    },
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: 'white',
            bodyColor: 'white',
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
                title: function(context) {
                    return `Bulan: ${context[0].label}`;
                },
                label: function(context) {
                    return `Aplikasi: ${context.parsed.y}`;
                }
            }
        }
    }
}

const createChart = () => {
    if (!chartCanvas.value || !props.data.labels || !props.data.datasets) return

    const ctx = chartCanvas.value.getContext('2d')
    
    // Destroy existing chart
    if (chartInstance) {
        chartInstance.destroy()
    }

    const mergedOptions = {
        ...defaultOptions,
        ...props.options
    }

    chartInstance = new ChartJS(ctx, {
        type: 'line',
        data: props.data,
        options: mergedOptions
    })
}

onMounted(() => {
    nextTick(() => {
        createChart()
    })
})

watch(() => props.data, () => {
    createChart()
}, { deep: true })
</script>

<style scoped>
.chart-container {
    position: relative;
    height: 350px;
    width: 100%;
}
</style>
