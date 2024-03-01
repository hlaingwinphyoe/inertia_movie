<template>
    <el-dialog
        :modelValue="show"
        @update:modelValue="show = $event"
        @close="closeDialog"
        @open="openDialog"
        draggable
        :title="dialogTitle"
        :close-on-click-modal="true"
    >
        <div class="aspect-w-16 aspect-h-9" v-html="data"></div>
    </el-dialog>
</template>

<script>
import { reactive, toRefs } from "vue";
export default {
    props: ["show", "title", "data"],
    emits: ["closed"],
    setup(props, context) {
        const state = reactive({
            dialogTitle: "",
        });

        const closeDialog = () => {
            context.emit("closed");
        };

        const openDialog = () => {
            state.dialogTitle = props.title;
        };

        return {
            ...toRefs(state),
            openDialog,
            closeDialog,
        };
    },
};
</script>

<style></style>
