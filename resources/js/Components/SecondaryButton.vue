<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-transparent border border-secondary-500 rounded-md font-semibold text-xs text-gray-200 uppercase tracking-widest shadow-sm hover:bg-secondary-800 focus:outline-none focus:ring-2 focus:ring-secondary-700 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
