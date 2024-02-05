<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? "w-full inline-flex items-center px-4 py-2.5 text-primary-500 hover:text-primary-600 text-sm font-bold leading-5 focus:outline-none uppercase focus:border-indigo-700 transition duration-150 ease-in-out"
        : "w-full inline-flex items-center p-4 py-2.5 text-sm font-medium leading-5 text-white hover:text-white hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
