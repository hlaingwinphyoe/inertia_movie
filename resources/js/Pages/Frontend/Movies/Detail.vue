<template>
    <FrontLayout>
        <div class="container mx-auto mt-6">
            <div class="flex items-center gap-8 mb-4">
                <img
                    :src="movie.thumbnail"
                    class="rounded-lg max-h-[400px]"
                    alt=""
                />
                <article>
                    <h4 class="text-3xl mb-1 font-bold">
                        {{ movie.title }} ({{ movie.release_year }})
                    </h4>
                    <div
                        class="flex items-end mb-4 gap-2 text-sm text-gray-400"
                    >
                        {{ movie.release_date }} ({{ movie.country }})
                        <font-awesome-icon
                            :icon="['fas', 'circle']"
                            class="text-[4px] mb-1"
                        />
                        <el-tag type="info" effect="plain" size="small">
                            {{ movie.video_quality }}
                        </el-tag>
                    </div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-2 mr-4">
                            <el-progress
                                :width="60"
                                :stroke-width="4"
                                type="circle"
                                :percentage="movie.rating * 10"
                                status="success"
                                class="bg-secondary-800 rounded-full"
                            >
                                <span class="text-sm font-bold">{{
                                    movie.rating
                                }}</span>
                            </el-progress>
                            <span class="text-sm"
                                >Rating <br />
                                Score</span
                            >
                        </div>
                        <el-tooltip
                            class="box-item"
                            content="Add To Watchlist"
                            placement="top"
                        >
                            <el-button
                                circle
                                type="primary"
                                bg
                                text
                                size="large"
                            >
                                <font-awesome-icon
                                    :icon="['far', 'bookmark']"
                                />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip
                            class="box-item"
                            content="Mark As Favorite"
                            placement="top"
                        >
                            <el-button
                                circle
                                type="primary"
                                bg
                                text
                                size="large"
                            >
                                <font-awesome-icon :icon="['far', 'heart']" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip
                            class="box-item"
                            content="Give Rate"
                            placement="top"
                        >
                            <el-button
                                circle
                                type="primary"
                                bg
                                text
                                size="large"
                            >
                                <font-awesome-icon :icon="['far', 'star']" />
                            </el-button>
                        </el-tooltip>
                        <el-button
                            type="info"
                            link
                            @click="handleTrailer(movie.trailer_video)"
                        >
                            <font-awesome-icon
                                :icon="['fas', 'play']"
                                class="mr-2"
                            />
                            Play Trailer
                        </el-button>
                    </div>
                    <div class="text-sm mb-6">
                        <h5 class="text-base mb-1">Overview</h5>
                        <p class="text-gray-400">
                            {{ movie.description }}
                        </p>
                    </div>

                    <div class="flex justify-between">
                        <p class="text-sm" v-if="movie.genres">
                            <span class="font-bold">{{ movie.genres }}</span>
                            <br />
                            <span class="text-[13px] text-gray-400"
                                >Genres</span
                            >
                        </p>
                        <p class="text-sm" v-if="movie.director">
                            <span class="font-bold">{{ movie.director }}</span>
                            <br />
                            <span class="text-[13px] text-gray-400"
                                >Director</span
                            >
                        </p>
                        <p class="text-sm" v-if="movie.running_time">
                            <span class="font-bold"
                                >{{ movie.running_time }} min</span
                            >
                            <br />
                            <span class="text-[13px] text-gray-400">Time</span>
                        </p>
                    </div>
                </article>
            </div>
            <div class="grid grid-cols-6 gap-6 divide-x divide-secondary-600">
                <div class="col-span-5 mb-4">
                    <h4 class="text-lg font-semibold mb-2">Top Casts</h4>
                    <div class="grid grid-cols-6 overflow-scroll gap-4 mb-4">
                        <CastCard
                            v-for="cast in movie.casts"
                            :key="cast.id"
                            :cast="cast"
                        />
                    </div>

                    <!-- Discover -->
                    <Discover />
                </div>
                <div class="pl-3 shadow">
                    <RelatedMovie
                        :relatedMovies="relatedMovies"
                        v-if="relatedMovies"
                    />
                    <el-empty description="No Related Movies" v-else />
                </div>
            </div>
        </div>

        <TrailerDialog
            :show="showTrailer"
            @closed="closeDialog"
            :title="dialog.dialogTitle"
            :data="dialog.dialogData"
        />
    </FrontLayout>
</template>

<script>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import CastCard from "../Composables/CastCard.vue";
import Discover from "./Discover.vue";
import TrailerDialog from "./TrailerDialog.vue";
import { reactive, toRefs } from "vue";
import RelatedMovie from "./RelatedMovie.vue";
export default {
    components: {
        FrontLayout,
        CastCard,
        Discover,
        TrailerDialog,
        RelatedMovie,
    },
    props: ["movie", "relatedMovies"],
    setup() {
        const state = reactive({
            dialog: {
                dialogTitle: "",
                dialogData: "",
            },
            showTrailer: false,
        });

        const handleTrailer = (row) => {
            state.showTrailer = true;
            state.dialog.dialogTitle = "Play Trailer";
            state.dialog.dialogData = row;
        };

        const closeDialog = () => {
            state.showTrailer = false;
        };

        return {
            ...toRefs(state),
            handleTrailer,
            closeDialog,
        };
    },
};
</script>

<style></style>
