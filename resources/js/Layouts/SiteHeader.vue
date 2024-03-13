<template>
    <header class="border-b border-gray-800 shadow">
        <nav class="container mx-auto px-4 lg:px-6 py-1.5">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <Link :href="route('home')" class="flex items-center gap-2">
                        <ApplicationLogo class="h-8" />

                        <h4 class="text-xl uppercase font-bold">Movie</h4>
                    </Link>
                    <div class="flex items-center">
                        <NavLink
                            href="/"
                            :active="route().current('dashboard')"
                        >
                            <span class="whitespace-nowrap text-sm">Movie</span>
                        </NavLink>
                        <NavLink
                            href="/"
                            :active="route().current('dashboard')"
                        >
                            <span class="whitespace-nowrap text-sm"
                                >About Us</span
                            >
                        </NavLink>
                        <NavLink
                            href="/"
                            :active="route().current('dashboard')"
                        >
                            <span class="whitespace-nowrap text-sm"
                                >Contact</span
                            >
                        </NavLink>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <el-button circle @click="openSearchDialog">
                        <font-awesome-icon
                            :icon="['fas', 'magnifying-glass']"
                        />
                    </el-button>
                    <div v-if="canLogin">
                        <div v-if="auth.user">
                            <el-dropdown>
                                <span class="text-[28px]">
                                    <font-awesome-icon
                                        :icon="['fas', 'circle-user']"
                                    />
                                </span>
                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item v-if="auth.user.is_admin">
                                            <Link
                                                :href="route('dashboard')"
                                                class="tracking-wide"
                                            >
                                                Dashboard
                                            </Link>
                                        </el-dropdown-item>
                                        <el-dropdown-item>
                                            <Link
                                                :href="route('dashboard')"
                                                class="tracking-wide"
                                            >
                                                Profile
                                            </Link>
                                        </el-dropdown-item>
                                        <el-dropdown-item divided>
                                            <a
                                                href="javascript:void(0)"
                                                @click="handleLogout"
                                            >
                                                <font-awesome-icon
                                                    :icon="[
                                                        'fas',
                                                        'right-from-bracket',
                                                    ]"
                                                />
                                                Log Out
                                            </a>
                                        </el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </div>

                        <div v-else>
                            <el-button
                                @click="handleLogin"
                                type="primary"
                                round
                                plain
                            >
                                Sign in
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <Login :show="showLogin" @closed="closeDialog" />
        <Search :show="showDialog" @closed="closeSearchDialog" />
    </header>
</template>

<script>
import { onMounted, reactive, toRefs } from "vue";
import { initFlowbite } from "flowbite";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import Login from "@/Pages/Auth/Login.vue";
import Search from "@/Pages/Frontend/Composables/Search.vue";
import { ElMessage } from "element-plus";
export default {
    setup() {
        const state = reactive({
            canLogin: usePage().props.canLogin,
            auth: usePage().props.auth,
            showLogin: false,
            showDialog: false,
        });

        onMounted(() => {
            initFlowbite();
            window.addEventListener("keydown", handleKeydown);
        });

        const handleLogin = () => {
            state.showLogin = true;
        };

        const closeDialog = () => {
            state.showLogin = false;
        };

        const openSearchDialog = () => {
            state.showDialog = true;
        };

        const closeSearchDialog = () => {
            state.showDialog = false;
        };

        const handleKeydown = (e) => {
            if (state.showDialog) return;
            if ((e.metaKey || e.ctrlKey) && e.key === "k") {
                openSearchDialog();
            }
        };

        const handleLogout = () => {
            router.post(
                route("logout"),
                {},
                {
                    onSuccess: (page) => {
                        ElMessage.success(page.props.flash.success);
                    },
                    onError: (page) => {
                        ElMessage.error(page.props.flash.error);
                    },
                    onFinish: () => window.location.reload(),
                }
            );
        };

        return {
            ...toRefs(state),
            handleLogin,
            closeDialog,
            openSearchDialog,
            closeSearchDialog,
            handleLogout,
        };
    },
    components: { ApplicationLogo, NavLink, Login, Link, Search },
};
</script>

<style scoped>
.example-showcase .el-dropdown-link {
    cursor: pointer;
    color: var(--el-color-primary);
    display: flex;
    align-items: center;
}
</style>
