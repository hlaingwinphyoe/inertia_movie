<template>
    <FrontLayout>
        <div class="sticky top-0 z-40 shadow-lg bg-[#1a191f] p-3.5 my-5">
            <div class="flex gap-4 items-center container mx-auto">
                <div class="relative w-full">
                    <font-awesome-icon
                        :icon="['fas', 'magnifying-glass']"
                        class="absolute left-5 top-[13px]"
                    />
                    <TextInput
                        class="w-full pl-11"
                        placeholder="Search for a movie, TV show or person"
                        v-model="param.search"
                        @keyup.down="searchMovies"
                        @keyup.enter="searchMovies"
                    />
                </div>
            </div>
        </div>
        <div class="container mx-auto my-5">
            <div class="my-5">
                <div class="flex justify-between items-center mb-4">
                    <h4
                        class="font-bold bg-gradient-to-tr from-primary-600 to-primary-700 w-fit px-5 rounded-full"
                    >
                        Movies
                    </h4>
                </div>
                <div v-if="moviesList.length">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"
                    >
                        <div v-for="movie in moviesList" :key="movie.id">
                            <Link
                                :href="route('movies.detail', movie.id)"
                                class="flex flex-col items-start border rounded-lg md:flex-row md:max-w-xl border-secondary-700 bg-secondary-700 hover:scale-95 hover:shadow-sm hover:shadow-secondary-700 transition-transform duration-200 ease-out"
                            >
                                <img
                                    class="object-cover w-full rounded-t-lg h-20 md:h-36 md:w-28 md:rounded-none md:rounded-s-lg"
                                    :src="movie.thumbnail"
                                    alt=""
                                />
                                <div
                                    class="flex justify-center flex-col p-2 leading-normal"
                                >
                                    <h5
                                        class="text-base font-bold tracking-tight text-white"
                                    >
                                        {{ movie.title }}
                                    </h5>
                                    <p class="text-xs text-gray-400 mb-2">
                                        {{ movie.release_date }}
                                    </p>
                                    <p
                                        class="text-sm font-normal text-secondary-400"
                                    >
                                        {{ movie.excerpt }}
                                    </p>
                                </div>
                            </Link>
                        </div>
                    </div>
                    <div
                        class="flex justify-center items-center my-5"
                        v-if="param.page < last_page"
                    >
                        <div v-if="loading">
                            <svg
                                aria-hidden="true"
                                class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                viewBox="0 0 100 101"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor"
                                />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill"
                                />
                            </svg>
                        </div>
                        <el-button
                            v-else
                            round
                            type="primary"
                            size="large"
                            @click="seeMore"
                            >See More</el-button
                        >
                    </div>
                </div>
                <el-empty description="No Movies" v-else />
            </div>
        </div>
    </FrontLayout>
</template>

<script>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { reactive, toRefs, watch } from "vue";
import debounce from "lodash.debounce";
import { router, Link } from "@inertiajs/vue3";
export default {
    components: { FrontLayout, TextInput, Link },
    props: ["movies", "query"],
    setup(props) {
        const state = reactive({
            loading: false,
            last_page: props.movies ? props.movies.meta.last_page : 1,
            param: {
                page: 1,
                page_size: 9,
                search: props.query ? props.query : "",
            },
            moviesList: props.movies ? props.movies.data : [],
        });

        watch(
            () => state.param.search,
            debounce(() => {
                searchMovies();
            }, 500)
        );

        const searchMovies = () => {
            state.loading = true;
            router.get(
                route("search", { query: state.param.search }),
                {},
                {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: (page) => {
                        state.moviesList = page.props.movies.data;
                        state.last_page = page.props.movies.meta.last_page;
                    },
                    onFinish: () => {
                        state.loading = false;
                    },
                }
            );
        };

        const seeMore = () => {
            if (state.last_page > state.param.page) {
                state.param.page += 1;
                state.loading = true;
                router.get(
                    route("search"),
                    {
                        page_size: state.param.page_size,
                        page: state.param.page,
                        query: state.param.search,
                    },
                    {
                        preserveScroll: true,
                        preserveState: true,
                        onSuccess: (page) => {
                            state.loading = false;
                            state.moviesList.push(...page.props.movies.data);
                        },
                    }
                );
            }
        };

        return {
            ...toRefs(state),
            searchMovies,
            seeMore,
        };
    },
};
</script>

<style></style>
