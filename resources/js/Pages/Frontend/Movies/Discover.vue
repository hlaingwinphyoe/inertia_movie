<template>
    <div class="mb-5">
        <h4 class="text-lg font-semibold mb-1">Discover</h4>
        <el-tabs v-model="activeName" class="demo-tabs">
            <el-tab-pane label="Comments" name="first">Comment</el-tab-pane>
            <el-tab-pane label="Reviews" name="second">Review</el-tab-pane>
            <el-tab-pane label="Photos" name="third">Photos</el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import { reactive, toRefs } from "vue";
export default {
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
