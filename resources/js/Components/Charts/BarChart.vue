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
    BarElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js/auto'

// Register Chart.js components - using auto import for all controllers
ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
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
                    size: 11
                },
                maxRotation: 45,
                minRotation: 0
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
            position: 'top',
            labels: {
                padding: 15,
                usePointStyle: true,
                font: {
                    size: 12
                }
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: 'white',
            bodyColor: 'white',
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true
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
        type: 'bar',
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
    height: 400px;
    width: 100%;
}
</style>
