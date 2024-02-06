<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-secondary-500 bg-transparent focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
        v-model="model"
        ref="input"
    />
</template>
