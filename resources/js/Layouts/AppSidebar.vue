<template>
    <div
        class="border-r border-secondary-700 h-screen block divide-y divide-secondary-700"
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
                href="/admin/categories"
                :active="route().current('admin.categories.index')"
            >
                <font-awesome-icon :icon="['fas', 'layer-group']" />
                <span class="ml-3">Category</span>
            </NavLink>
            <NavLink
                href="/admin/types"
                :active="route().current('admin.types.index')"
            >
                <font-awesome-icon :icon="['fas', 'grip']" />
                <span class="ml-3">Type</span>
            </NavLink>
        </div>
    </div>
</template>

<script>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { reactive, toRefs } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";

export default {
    components: { ApplicationLogo, NavLink },
    setup() {
        const state = reactive({
            auth: usePage().props.auth,
        });

        return {
            ...toRefs(state),
        };
    },
};
</script>

<style></style>
