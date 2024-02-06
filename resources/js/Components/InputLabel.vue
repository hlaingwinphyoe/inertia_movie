<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="mb-1.5 block font-medium text-sm text-gray-300">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
