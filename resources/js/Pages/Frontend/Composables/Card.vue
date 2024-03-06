<template>
    <Link
        :href="route('movies.detail', movie.id)"
        class="relative cursor-pointer"
    >
        <el-card
            class="h-[230px] w-fit relative group"
            style="border-radius: 8px !important"
            :body-style="{ padding: '0px' }"
        >
            <div
                class="z-50 absolute hidden items-center justify-center h-full w-full text-4xl bg-gray-900/20 group-hover:flex transition duration-300 ease-in-out"
            >
                <span
                    class="opactiy-0 group-hover:opacity-100 w-10 h-10 relative transition-opacity duration-300 ease-in-out"
                >
                    <font-awesome-icon
                        :icon="['fas', 'circle-play']"
                        class="z-50"
                    />
                </span>
            </div>
            <img
                :src="movie.thumbnail"
                class="h-56 w-44 object-cover group-hover:blur-[2px]"
            />
            <div class="absolute top-3 left-3">
                <el-progress
                    :width="35"
                    :stroke-width="2.5"
                    type="circle"
                    :percentage="movie.rating * 10"
                    status="success"
                    class="bg-black rounded-full"
                >
                    <span class="text-xs mr-4 font-bold">{{
                        movie.rating
                    }}</span>
                </el-progress>
            </div>
        </el-card>
        <div class="mt-2 mb-4 text-sm">
            <!-- <el-text truncated>{{
                movie.title
            }}</el-text> -->
            <span class="font-bold text-sm truncate w-fit">
                {{ truncate(movie.title) }}
            </span>
            <div class="text-secondary-400 text-[13px]">
                {{ movie.release_date }}
            </div>
        </div>
    </Link>
</template>

<script>
import { Link } from "@inertiajs/vue3";
export default {
    props: ["movie"],
    components: { Link },
    setup() {
        const truncate = (text) => {
            if (text.length > 23) {
                return text.substring(0, 20) + "...";
            }

            return text;
        };

        return {
            truncate,
        };
    },
};
</script>

<style></style>
