<script setup>
/**
 * StatusBadge Component
 *
 * Displays application status with appropriate styling, icon, and color.
 * Supports both simple string status and full status_info object from backend.
 *
 * Usage:
 * <StatusBadge :status="application.status" :status-info="application.status_info" />
 *
 * Or with just status (will use default styling):
 * <StatusBadge status="accepted" />
 */

import { computed } from "vue";

const props = defineProps({
    // The status string value (e.g., 'menunggu', 'accepted', 'rejected')
    status: {
        type: String,
        required: true,
    },
    // Full status info object from backend (optional, provides label, badge_classes, icon, color)
    statusInfo: {
        type: Object,
        default: null,
    },
    // Display size: 'sm', 'md', 'lg'
    size: {
        type: String,
        default: "md",
        validator: (value) => ["sm", "md", "lg"].includes(value),
    },
    // Whether to show the icon
    showIcon: {
        type: Boolean,
        default: true,
    },
    // Whether to show as pill (rounded) or badge (less rounded)
    pill: {
        type: Boolean,
        default: true,
    },
});

// Default status configurations when statusInfo is not provided
const defaultStatusConfig = {
    menunggu: {
        label: "Menunggu",
        color: "yellow",
        icon: "clock",
        badge_classes: "bg-yellow-100 text-yellow-800 border-yellow-200",
    },
    in_review: {
        label: "Dalam Review",
        color: "blue",
        icon: "search",
        badge_classes: "bg-blue-100 text-blue-800 border-blue-200",
    },
    interview_scheduled: {
        label: "Wawancara Dijadwalkan",
        color: "purple",
        icon: "calendar",
        badge_classes: "bg-purple-100 text-purple-800 border-purple-200",
    },
    accepted: {
        label: "Diterima",
        color: "green",
        icon: "check-circle",
        badge_classes: "bg-green-100 text-green-800 border-green-200",
    },
    rejected: {
        label: "Ditolak",
        color: "red",
        icon: "x-circle",
        badge_classes: "bg-red-100 text-red-800 border-red-200",
    },
    letter_ready: {
        label: "Surat Siap",
        color: "emerald",
        icon: "document",
        badge_classes: "bg-emerald-100 text-emerald-800 border-emerald-200",
    },
    expired: {
        label: "Kadaluarsa",
        color: "gray",
        icon: "ban",
        badge_classes: "bg-gray-100 text-gray-600 border-gray-200",
    },
};

// Computed status configuration
const config = computed(() => {
    if (props.statusInfo) {
        return props.statusInfo;
    }
    return (
        defaultStatusConfig[props.status] || {
            label: props.status,
            color: "gray",
            icon: "question-mark",
            badge_classes: "bg-gray-100 text-gray-800 border-gray-200",
        }
    );
});

// Size classes
const sizeClasses = computed(() => {
    const sizes = {
        sm: "px-2 py-0.5 text-xs",
        md: "px-2.5 py-1 text-sm",
        lg: "px-3 py-1.5 text-base",
    };
    return sizes[props.size];
});

// Icon size classes
const iconSizeClasses = computed(() => {
    const sizes = {
        sm: "w-3 h-3",
        md: "w-4 h-4",
        lg: "w-5 h-5",
    };
    return sizes[props.size];
});

// Border radius classes
const radiusClasses = computed(() => {
    return props.pill ? "rounded-full" : "rounded-md";
});

// Combined classes
const badgeClasses = computed(() => {
    const base = "inline-flex items-center gap-1.5 font-medium border";
    const custom =
        config.value.badge_classes ||
        "bg-gray-100 text-gray-800 border-gray-200";
    return `${base} ${sizeClasses.value} ${radiusClasses.value} ${custom}`;
});

// Icon component mapping
const iconPaths = {
    clock: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
    search: "M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z",
    calendar:
        "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z",
    "check-circle": "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    "x-circle":
        "M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z",
    document:
        "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
    ban: "M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636",
    "question-mark":
        "M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    check: "M5 13l4 4L19 7",
    "arrow-right": "M14 5l7 7m0 0l-7 7m7-7H3",
};

const iconPath = computed(() => {
    const icon = config.value.icon || "question-mark";
    return iconPaths[icon] || iconPaths["question-mark"];
});
</script>

<template>
    <span :class="badgeClasses">
        <!-- Icon -->
        <svg
            v-if="showIcon"
            :class="iconSizeClasses"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path :d="iconPath" />
        </svg>

        <!-- Label -->
        <span>{{ config.label }}</span>
    </span>
</template>
