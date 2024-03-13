<template>
    <footer class="bg-secondary-800">
        <div class="mx-auto w-full max-w-screen-xl">
            <div
                class="grid grid-cols-2 gap-8 px-4 py-6 lg:py-8 md:grid-cols-4"
            >
                <div>
                    <ApplicationLogo class="h-20" />
                    <h2
                        class="mb-6 text-5xl font-semibold uppercase bg-gradient-to-tr from-primary-500 to-red-500 bg-clip-text text-transparent"
                    >
                        Inertia Movie
                    </h2>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibolduppercase text-white">
                        Help center
                    </h2>
                    <ul class="text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="#" class="hover:underline"
                                >Discord Server</a
                            >
                        </li>
                        <li class="mb-4">
                            <a href="#" class="hover:underline">Twitter</a>
                        </li>
                        <li class="mb-4">
                            <a href="#" class="hover:underline">Facebook</a>
                        </li>
                        <li class="mb-4">
                            <a href="#" class="hover:underline">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold uppercase text-white">
                        Legal
                    </h2>
                    <ul class="text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="#" class="hover:underline"
                                >Privacy Policy</a
                            >
                        </li>
                        <li class="mb-4">
                            <a href="#" class="hover:underline">Licensing</a>
                        </li>
                        <li class="mb-4">
                            <a href="#" class="hover:underline"
                                >Terms &amp; Conditions</a
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibolduppercase text-white">
                        Download
                    </h2>
                    <ul class="text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="#" class="hover:underline">iOS</a>
                        </li>
                        <li class="mb-4">
                            <a href="#" class="hover:underline">Android</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="px-4 py-6 bg-gray-700 md:flex md:items-center md:justify-between"
            >
                <span class="text-sm text-gray-300 sm:text-center"
                    >© {{ currentYear }} <Link href="/">Inertia Movies</Link>.
                    All Rights Reserved.
                </span>
                <div
                    class="flex mt-4 sm:justify-center md:mt-0 space-x-5 rtl:space-x-reverse"
                >
                    <a href="#" class="text-gray-400 hover:text-white">
                        <font-awesome-icon :icon="['fab', 'facebook']" />
                        <span class="sr-only">Facebook page</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <font-awesome-icon :icon="['fab', 'discord']" />
                        <span class="sr-only">Discord community</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <font-awesome-icon :icon="['fab', 'x-twitter']" />
                        <span class="sr-only">Twitter page</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <font-awesome-icon :icon="['fab', 'github']" />
                        <span class="sr-only">GitHub account</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
import { onMounted, reactive, toRefs } from "vue";

export default {
    components: {
        ApplicationLogo,
        Link,
    },
    setup() {
        const state = reactive({
            currentYear: 2024,
        });
        onMounted(() => {
            let currentDate = new Date();
            state.currentYear = currentDate.getFullYear();
        });

        return {
            ...toRefs(state),
        };
    },
};
</script>

<style></style>
