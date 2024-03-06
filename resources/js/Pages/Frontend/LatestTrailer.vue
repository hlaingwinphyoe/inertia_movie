<template>
    <div class="bg-secondary-800 p-6 rounded-xl">
        <h4 class="text-xl font-bold">Latest Trailers</h4>
        <div>
            <el-carousel :interval="4000" type="card">
                <el-carousel-item v-for="item in trailer_videos" :key="item.id">
                    <div
                        class="aspect-w-16 aspect-h-9"
                        v-html="item.trailer_video"
                    ></div>
                </el-carousel-item>
            </el-carousel>
        </div>
    </div>
</template>

<script>
export default {
    props: ["trailer_videos"],
};
</script>

<style></style>
