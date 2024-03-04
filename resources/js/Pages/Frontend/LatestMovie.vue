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
            </div>
        </div>
        <div
            class="mt-4 flex items-center gap-2 relative"
            v-loading="isLoading"
            element-loading-text="Loading..."
            element-loading-background="rgba(0, 0, 0, 0.8)"
            style="width: 100%"
        >
            <div
                class="hidden lg:block absolute -left-8 cursor-pointer mb-10"
                @click.prevent="prev"
            >
                <font-awesome-icon
                    :icon="['fas', 'circle-chevron-left']"
                    size="lg"
                />
            </div>
            <Carousel
                ref="refCarousel"
                v-bind="settings"
                :breakpoints="breakpoints"
                :itemsToScroll="2"
                :autoplay="2000"
                :touchDrag="false"
            >
                <Slide v-for="movie in movieLists" :key="movie.id">
                    <div class="carousel__item">
                        <Card :movie="movie" />
                    </div>
                </Slide>
            </Carousel>
            <div
                class="hidden lg:block absolute -right-8 cursor-pointer mb-10"
                @click.prevent="next"
            >
                <font-awesome-icon
                    :icon="['fas', 'circle-chevron-right']"
                    size="lg"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref, toRefs } from "vue";
import Card from "./Composables/Card.vue";
import { Carousel, Slide } from "vue3-carousel";

import "vue3-carousel/dist/carousel.css";
import { router } from "@inertiajs/vue3";
export default {
    props: ["latest_movies"],
    components: { Card, Carousel, Slide },
    setup(props) {
        const state = reactive({
            title: "Today",
            movieLists: props.latest_movies ? props.latest_movies : [],
            settings: {
                itemsToShow: 1,
                snapAlign: "center",
            },
            isLoading: false,
            breakpoints: {
                700: {
                    itemsToShow: 3.5,
                    snapAlign: "center",
                },
                1024: {
                    itemsToShow: 5.5,
                    snapAlign: "center",
                },
            },
        });

        const toggleMovie = (value) => {
            state.title = value;
            tabActive();
            getData(value);
        };

        const getData = (param) => {
            state.isLoading = true;
            router.get(
                route("home", { q: param }),
                {},
                {
                    preserveState: true,
                    replace: true,
                    onSuccess: (page) => {
                        state.movieLists = page.props.latest_movies;
                    },
                    onFinish: () => {
                        state.isLoading = false;
                    },
                }
            );
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

        const refCarousel = ref(null);

        const next = () => {
            refCarousel.value.data.next();
        };

        const prev = () => {
            refCarousel.value.data.prev();
        };

        onMounted(() => {
            tabActive();
            getData("Today");
        });

        return {
            ...toRefs(state),
            toggleMovie,
            next,
            prev,
            refCarousel,
        };
    },
};
</script>

<style scoped>
.indicator {
    transition: left 0.4s;
}
</style>
