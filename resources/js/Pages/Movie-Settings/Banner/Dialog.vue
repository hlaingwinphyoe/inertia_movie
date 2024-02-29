<template>
    <el-dialog
        :modelValue="show"
        @update:modelValue="show = $event"
        @close="closeDialog(formRef)"
        @open="openDialog"
        draggable
        :title="dialogTitle"
        :close-on-click-modal="false"
    >
        <el-form
            label-width="120px"
            ref="formRef"
            :model="form"
            label-position="top"
        >
            <el-form-item label="Photos" size="large">
                <el-upload
                    v-model="form.fileList"
                    list-type="picture-card"
                    :on-remove="handleRemove"
                    :on-change="handleFileChange"
                    accept="image/*"
                    :limit="1"
                    :auto-upload="false"
                >
                    <el-icon><Plus /></el-icon>
                </el-upload>
            </el-form-item>
        </el-form>
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="closeDialog(formRef)">Cancel</el-button>
                <el-button type="primary" @click="submitDialog(formRef)">
                    Save
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import { reactive, ref, toRefs } from "vue";
import { router } from "@inertiajs/vue3";
import { ElMessage } from "element-plus";
import { Plus, Delete } from "@element-plus/icons-vue";
export default {
    props: ["show", "title", "data"],
    emits: ["closed"],
    components: { Plus, Delete },
    setup(props, context) {
        const state = reactive({
            dialogTitle: "",
            doubleClick: false,
            form: {
                fileList: "",
            },
            virtualForm: new FormData(),
        });

        const formRef = ref();

        const submitDialog = (formRef) => {
            formRef.validate((valid) => {
                if (valid) {
                    if (state.dialogTitle === "Create") {
                        state.doubleClick = true;
                        state.virtualForm.append(
                            "banner_image",
                            state.form.fileList
                        );
                        router.post(
                            route("admin.banners.store"),
                            state.virtualForm,
                            {
                                onSuccess: (page) => {
                                    state.doubleClick = false;
                                    closeDialog(formRef);
                                    ElMessage.success(page.props.flash.success);
                                },
                                onError: () => {
                                    state.doubleClick = false;
                                },
                            }
                        );
                    }
                }
            });
        };

        const closeDialog = (formRef) => {
            state.virtualForm = new FormData();
            state.form = {}
            formRef.resetFields();
            context.emit("closed");
        };

        const openDialog = () => {
            state.dialogTitle = props.title;
        };

        const handleFileChange = (file) => {
            state.form.fileList = file.raw;
        };

        const handleRemove = (uploadFile) => {
            console.log("here");
        };

        return {
            ...toRefs(state),
            openDialog,
            formRef,
            closeDialog,
            submitDialog,
            handleFileChange,
            handleRemove,
        };
    },
};
</script>

<style></style>
