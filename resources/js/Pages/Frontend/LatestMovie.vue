<template>
    <div>
        <div class="flex items-center gap-5">
            <h4 class="text-xl font-bold">Latest Movies</h4>
            <div
                class="relative w-max flex items-center rounded-full border border-primary-700 text-xs"
            >
                <div
                    class="absolute indicator h-7 my-auto top-0 bottom-0 left-0 rounded-full bg-gradient-to-tr from-primary-700 to-primary-500 transition-all duration-500"
                ></div>

                <div
                    class="px-6 py-[7px] rounded-full cursor-pointer tab z-10"
                    @click="toggleMovie('Today')"
                >
                    Today
                </div>
                <div
                    class="px-6 py-[7px] rounded-full cursor-pointer tab z-10"
                    @click="toggleMovie('This Week')"
                >
                    This Week
                </div>

                <!-- <div
                    class="px-6 py-1.5 rounded-full cursor-pointer transition-transform duration-500 ease-in-out"
                    :class="checkActive('Today')"
                    @click="toggleMovie('Today')"
                >
                    Today
                </div>
                <div
                    class="px-6 py-1.5 rounded-full cursor-pointer transition-transform duration-500 ease-in-out"
                    :class="checkActive('This Week')"
                    @click="toggleMovie('This Week')"
                >
                    This Week
                </div> -->
            </div>
        </div>
        <div class="mt-5 grid grid-cols-6">
            <Card v-for="movie in movieLists" :key="movie.id" :movie="movie" />
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from "vue";
import Card from "./Composables/Card.vue";
export default {
    props: ["latest_movies"],
    components: { Card },
    setup(props) {
        const state = reactive({
            title: "Today",
            movieLists: props.latest_movies ? props.latest_movies : [],
        });

        const toggleMovie = (value) => {
            state.title = value;
            tabActive();
        };

        const tabActive = () => {
            let tabs = document.querySelectorAll(".tab");
            let indicator = document.querySelector(".indicator");

            indicator.style.width =
                tabs[0].getBoundingClientRect().width + "px";
            indicator.style.left =
                tabs[0].getBoundingClientRect().left -
                tabs[0].parentElement.getBoundingClientRect().left +
                "px";

            tabs.forEach((tab) => {
                tab.addEventListener("click", () => {
                    indicator.style.width =
                        tab.getBoundingClientRect().width + "px";
                    indicator.style.left =
                        tab.getBoundingClientRect().left -
                        tab.parentElement.getBoundingClientRect().left +
                        "px";
                });
            });
        };

        onMounted(() => {
            tabActive();
        });

        return {
            ...toRefs(state),
            toggleMovie,
        };
    },
};
</script>

<style scoped>
.indicator {
    transition: left 0.4s;
}
</style>
