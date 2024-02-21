<template>
    <FrontLayout>
        <div class="container mx-auto h-screen">
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
                    />
                    <el-button
                        style="width: 100px !important"
                        size="large"
                        type="primary"
                        round
                        >Search</el-button
                    >
                </div>
            </div>

            <!-- Latest Movie -->
            <LatestMovie :latest_movies="latest_movies" />
        </div>
    </FrontLayout>
</template>

<script>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { reactive, toRefs } from "vue";
import LatestMovie from "./Frontend/LatestMovie.vue";

export default {
    props: ["latest_movies"],
    components: { FrontLayout, TextInput, LatestMovie },
    setup(props) {
        const state = reactive({
            search: "",
        });

        return {
            ...toRefs(state),
        };
    },
};
</script>

<style>
.bg-url {
    background-image: url("/home-bg.webp");
}
</style>
