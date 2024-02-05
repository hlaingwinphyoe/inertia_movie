<template>
    <header
        class="flex justify-between border-b py-[18px]"
    >
        <div class="container flex justify-between mx-auto">
            <div>
                <a
                    href="javascript:void(0)"
                    class="text-2xl mr-3"
                    @click="openSidebar"
                >
                    <font-awesome-icon icon="fa-solid fa-bars" />
                </a>
                <Link href="/dashboard">
                    <font-awesome-icon
                        :icon="['fas', 'compact-disc']"
                        class="text-2xl"
                    />
                </Link>
            </div>
            <div>
                <el-dropdown>
                    <el-button type="primary">
                        {{ auth.user.name }}
                    </el-button>
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item>Profile</el-dropdown-item>
                            <el-dropdown-item divided>Log Out</el-dropdown-item>
                        </el-dropdown-menu>
                    </template>
                </el-dropdown>
            </div>
        </div>
    </header>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";

const canLogin = usePage().props.canLogin;
const canRegister = usePage().props.canRegister;
const auth = usePage().props.auth;

defineEmits(["open"]);
</script>

<style>
.example-showcase .el-dropdown-link {
    cursor: pointer;
    color: var(--el-color-primary);
    display: flex;
    align-items: center;
}
</style>
