<template>
    <FrontLayout>
        <div class="container mx-auto mt-6">
            <div class="flex items-center gap-8">
                <img
                    :src="movie.thumbnail"
                    class="rounded-lg max-h-[400px]"
                    alt=""
                />
                <article>
                    <h4 class="text-2xl mb-3 font-bold">
                        {{ movie.title }} ({{ movie.release_year }})
                    </h4>
                    <p class="text-sm" v-if="movie.director">
                        <span class="text-gray-400">Director</span> -
                        {{ movie.director }} min
                    </p>
                    <div class="flex mb-2 gap-2 text-sm text-gray-400">
                        Genres -
                        <el-tag
                            type="info"
                            v-for="genre in movie.genres"
                            :key="genre.id"
                        >
                            {{ genre.name }}
                        </el-tag>
                    </div>
                    <div class="mb-4 space-y-1">
                        <p class="text-sm">
                            <span class="text-gray-400">Running Time</span> -
                            {{ movie.running_time }} min
                        </p>
                        <!-- <p class="text-sm">
                            <span class="text-gray-400">Views</span> -
                            {{ movie.views }}
                        </p> -->
                        <p class="text-sm">
                            <span class="text-gray-400">Quality</span> -
                            {{ movie.video_quality }}
                        </p>
                        <p class="text-sm">
                            <span class="text-gray-400">Country</span> -
                            {{ movie.country }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-2 mr-4">
                            <el-progress
                                :width="60"
                                :stroke-width="4"
                                type="circle"
                                :percentage="movie.rating * 10"
                                status="primary"
                                class="bg-black rounded-full"
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
                        <el-button type="info" link>
                            <font-awesome-icon
                                :icon="['fas', 'play']"
                                class="mr-2"
                            />
                            Play Trailer
                        </el-button>
                    </div>
                    <div class="text-sm">
                        <h5 class="text-base mb-1">Overview</h5>
                        <p class="text-gray-400">
                            {{ movie.description }}
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </FrontLayout>
</template>

<script>
import FrontLayout from "@/Layouts/FrontLayout.vue";
export default {
    components: { FrontLayout },
    props: ["movie"],
};
</script>

<style></style>
