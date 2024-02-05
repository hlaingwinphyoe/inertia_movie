<template>
    <div
        class="border-r border-gray-800 h-screen block divide-y divide-gray-800"
    >
        <div class="flex gap-4 items-center justify-center p-5">
            <ApplicationLogo class="h-7 text-white" />
            <h4 class="text-lg uppercase font-medium">Movie</h4>
        </div>
        <div class="p-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span
                    class="text-base px-3 py-1.5 rounded border border-primary-500 text-primary-500"
                >
                    <font-awesome-icon :icon="['fas', 'user']" />
                </span>
                <h4 class="flex flex-col">
                    <small class="text-[10px]">Developer</small>
                    <a href="#" class="text-sm">{{ auth.user.name }}</a>
                </h4>
            </div>
            <div>
                <a href="#" class="px-3 py-2 rounded">
                    <font-awesome-icon :icon="['fas', 'right-from-bracket']" />
                </a>
            </div>
        </div>
        <div class="pt-3">
            <el-menu active-text-color="#04A3D8">
                <Link href="/dashboard">
                    <el-menu-item index="/dashboard">
                        <font-awesome-icon icon="fa-solid fa-chart-pie" />
                        <span class="ml-3">Dashboard</span>
                    </el-menu-item>
                </Link>
                <el-sub-menu index="1">
                    <template #title>
                        <font-awesome-icon icon="fa-solid fa-gear" />
                        <span class="ml-3">Movie Settings</span>
                    </template>
                    <el-menu-item index="1-1">
                        <font-awesome-icon icon="fa-solid fa-film" />
                        <span class="ml-3">Movie Lists</span>
                    </el-menu-item>
                    <Link :href="route('admin.types.index')">
                        <el-menu-item index="1-1">
                            <font-awesome-icon icon="fa-solid fa-sliders" />
                            <span class="ml-3">Movie Type</span>
                        </el-menu-item>
                    </Link>
                    <Link :href="route('admin.categories.index')">
                        <el-menu-item index="/admin/categories">
                            <font-awesome-icon icon="fa-solid fa-layer-group" />
                            <span class="ml-3">Category</span>
                        </el-menu-item>
                    </Link>
                    <!-- <el-menu-item-group title="Group One">
                        <el-menu-item index="1-1">Item Type</el-menu-item>
                        <el-menu-item index="1-2">Category</el-menu-item>
                    </el-menu-item-group> -->
                    <!-- <el-menu-item-group title="Group Two">
                        <el-menu-item index="1-3">Genre</el-menu-item>
                    </el-menu-item-group>
                    <el-sub-menu index="1-4">
                        <template #title>item four</template>
                        <el-menu-item index="1-4-1">Category</el-menu-item>
                    </el-sub-menu> -->
                </el-sub-menu>
            </el-menu>
        </div>
    </div>
</template>

<script>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { reactive, toRefs } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";

export default {
    components: { ApplicationLogo, NavLink, Link },
    setup() {
        const state = reactive({
            auth: usePage().props.auth,
        });

        const active = (url) => {
            if (url == usePage().url) {
                return "active";
            }
        };

        return {
            ...toRefs(state),
            active,
        };
    },
};
</script>

<style></style>
