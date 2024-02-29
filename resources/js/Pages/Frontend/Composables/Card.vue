<template>
    <Link
        :href="route('movies.detail', movie.id)"
        class="relative cursor-pointer"
    >
        <el-card
            class="w-fit relative group"
            style="border-radius: 8px !important"
            :body-style="{ padding: '0px' }"
        >
            <div
                class="absolute hidden items-center justify-center h-full w-full text-4xl bg-gray-900/80 group-hover:flex transition-all ease-in-out"
            >
                <span class="w-10 h-10 relative">
                    <font-awesome-icon
                        :icon="['fas', 'circle-play']"
                        class="z-50"
                    />
                </span>
            </div>
            <img :src="data.thumbnail" class="h-56 w-44 object-cover" />
            <div class="absolute top-3 left-3">
                <el-progress
                    :width="35"
                    :stroke-width="2.5"
                    type="circle"
                    :percentage="data.rating * 10"
                    status="success"
                    class="bg-black rounded-full"
                >
                    <span class="text-xs mr-4 font-bold">{{
                        data.rating
                    }}</span>
                </el-progress>
            </div>
        </el-card>
        <div class="mt-2 text-sm">
            <span class="font-bold text-[14.5px]">{{ data.title }}</span>
            <div class="text-secondary-400 text-[13px]">
                {{ data.release_date }}
            </div>
        </div>
    </Link>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import { reactive, toRefs } from "vue";
export default {
    props: ["movie"],
    setup(props) {
        const state = reactive({
            data: props.movie ? props.movie : "",
        });
        return {
            ...toRefs(state),
        };
    },
    components: { Link },
};
</script>

<style></style>
