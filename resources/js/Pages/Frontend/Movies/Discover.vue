<template>
    <div class="mb-5">
        <h4 class="text-lg font-semibold mb-1">Discover</h4>
        <el-tabs v-model="activeName" class="demo-tabs">
            <el-tab-pane label="Comments" name="first">Comment</el-tab-pane>
            <el-tab-pane label="Reviews" name="second">Review</el-tab-pane>
            <el-tab-pane label="Posters" name="third">
                <div class="grid grid-cols-4 gap-2">
                    <el-image
                        class="max-h-[250px] rounded-lg"
                        v-for="url in photos"
                        :key="url"
                        :src="url"
                        :zoom-rate="1.2"
                        :max-scale="7"
                        :min-scale="0.2"
                        :preview-src-list="photos"
                        fit="cover"
                    />
                </div>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import { reactive, toRefs } from "vue";
export default {
    props: ["photos"],
    setup() {
        const state = reactive({
            activeName: "first",
        });

        return {
            ...toRefs(state),
        };
    },
};
</script>

<style></style>
