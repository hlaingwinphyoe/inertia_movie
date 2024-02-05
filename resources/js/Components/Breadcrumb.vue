<template>
    <el-breadcrumb separator="/" class="py-4">
        <el-breadcrumb-item :to="{ path: '/' }">
            <font-awesome-icon icon="fa-solid fa-house" class="text-white" />
        </el-breadcrumb-item>
        <slot />
    </el-breadcrumb>
</template>

<script>
export default {};
</script>

<style></style>
