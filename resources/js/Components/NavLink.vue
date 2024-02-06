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
        ? "w-full inline-flex items-center px-5 py-4 border-b border-gray-800 text-primary-500 hover:text-primary-500 text-sm font-bold leading-5 focus:outline-none transition duration-150 ease-in-out"
        : "w-full inline-flex items-center px-5 py-4 border-b border-gray-800 text-sm font-medium leading-5 text-gray-100 hover:text-white hover:font-bold focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
