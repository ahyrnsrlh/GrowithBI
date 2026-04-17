<script setup>
import DivisionIndexCard from "@/Components/Admin/Divisions/DivisionIndexCard.vue";

defineProps({
    divisions: {
        type: Array,
        required: true,
    },
    formatDate: {
        type: Function,
        required: true,
    },
    getProgressPercent: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(["confirm-delete"]);
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <DivisionIndexCard
            v-for="division in divisions"
            :key="division.id"
            :division="division"
            :format-date="formatDate"
            :get-progress-percent="getProgressPercent"
            @confirm-delete="emit('confirm-delete', $event)"
        />
    </div>
</template>
