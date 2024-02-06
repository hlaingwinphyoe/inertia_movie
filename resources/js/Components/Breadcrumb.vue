<template>
    <el-breadcrumb separator="/" class="py-4">
        <el-breadcrumb-item>
            <Link :href="route('dashboard')">
                <font-awesome-icon
                    icon="fa-solid fa-house"
                    class="text-white"
                />
            </Link>
        </el-breadcrumb-item>
        <slot />
    </el-breadcrumb>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default { components: { Link } };
</script>

<style></style>
