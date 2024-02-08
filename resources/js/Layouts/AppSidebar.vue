<template>
    <div
        class="border-r border-secondary-500 h-screen block divide-y divide-secondary-500"
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
                <a
                    href="javascript:void(0)"
                    @click="handleLogout"
                    class="px-3 py-2 rounded"
                >
                    <font-awesome-icon :icon="['fas', 'right-from-bracket']" />
                </a>
            </div>
        </div>
        <div>
            <NavLink href="/dashboard" :active="route().current('dashboard')">
                <font-awesome-icon icon="fa-solid fa-chart-pie" />
                <span class="ml-3">Dashboard</span>
            </NavLink>
            <NavLink
                href="/admin/movies"
                :active="route().current('admin.movies.*')"
            >
                <font-awesome-icon :icon="['fas', 'film']" />
                <span class="ml-3">Movies List</span>
            </NavLink>
            <NavLink
                href="/admin/genres"
                :active="route().current('admin.genres.index')"
            >
                <font-awesome-icon :icon="['fas', 'layer-group']" />
                <span class="ml-3">Genre</span>
            </NavLink>
            <NavLink
                href="/admin/tags"
                :active="route().current('admin.tags.index')"
            >
                <font-awesome-icon :icon="['fas', 'grip']" class="ml-0.5" />
                <span class="ml-3">Tags</span>
            </NavLink>
        </div>
    </div>
</template>

<script>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { reactive, toRefs } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import { ElMessage, ElMessageBox } from "element-plus";

export default {
    components: { ApplicationLogo, NavLink, Link },
    setup() {
        const state = reactive({
            auth: usePage().props.auth,
        });

        const handleLogout = () => {
            ElMessageBox.confirm(
                "Are you sure you want to logout?",
                "Warning",
                {
                    confirmButtonText: "Log Out",
                    cancelButtonText: "Cancel",
                    type: "warning",
                    draggable: true,
                    closeOnClickModal: false,
                }
            )
                .then(() => {
                    router.post(route("logout"), {
                        onSuccess: (page) => {
                            ElMessage.success(page.props.flash.success);
                        },
                        onError: (page) => {
                            ElMessage.error(page.props.flash.error);
                        },
                    });
                })
                .catch(() => {
                    ElMessage({
                        type: "info",
                        message: "Cancel",
                    });
                });
        };

        return {
            ...toRefs(state),
            handleLogout,
        };
    },
};
</script>

<style></style>
