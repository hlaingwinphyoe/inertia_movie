<template>
    <FrontLayout>
        <div class="container mx-auto min-h-screen">
            <div class="min-h-80 flex flex-col justify-center">
                <div class="mb-6">
                    <h1 class="text-5xl font-bold mb-4">Welcome.</h1>
                    <h2 class="text-3xl">
                        Millions of movies, TV shows and people to discover.
                        Explore now.
                    </h2>
                </div>
                <div class="flex gap-4 items-center">
                    <TextInput
                        class="w-full"
                        placeholder="Search for a movie, TV show or person"
                        v-model="search"
                        @keyup.enter="searchMovies"
                    />
                    <el-button
                        style="width: 100px !important"
                        size="large"
                        type="primary"
                        round
                        @click="searchMovies"
                        >Search</el-button
                    >
                </div>
            </div>

            <!-- Latest Movies-->
            <LatestMovie :latest_movies="latest_movies" />

            <!-- Latest Trailers -->
            <LatestTrailer :trailer_videos="trailer_videos" />

            <div class="my-5"></div>
        </div>
    </FrontLayout>
</template>

<script>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { reactive, toRefs } from "vue";
import LatestMovie from "./Frontend/LatestMovie.vue";
import LatestTrailer from "./Frontend/LatestTrailer.vue";
import { router } from "@inertiajs/vue3";

export default {
    props: ["latest_movies", "trailer_videos"],
    components: { FrontLayout, TextInput, LatestMovie, LatestTrailer },
    setup(props) {
        const state = reactive({
            search: "",
        });

        const searchMovies = () => {
            router.get(route("search", { query: state.search }));
        };

        return {
            ...toRefs(state),
            searchMovies,
        };
    },
};
</script>

<style>
.bg-url {
    background-image: url("/home-bg.webp");
}
</style>
