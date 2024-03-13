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
            <el-form-item
                label="Title"
                :rules="[
                    {
                        required: true,
                        message: 'Faq Type is required',
                        trigger: 'blur',
                    },
                ]"
            >
                <el-radio-group v-model="form.faq_type">
                    <el-radio
                        border
                        v-for="item in faq_types"
                        :key="item.id"
                        :label="item.id"
                        >{{ item.name }}
                    </el-radio>
                </el-radio-group>
                <div v-if="form.errors.faq_type" class="text-red-600">
                    {{ form.errors.faq_type }}
                </div>
            </el-form-item>
            <el-form-item label="Title" size="large">
                <el-input v-model="form.title" placeholder="Title" />
                <div v-if="form.errors.title" class="text-red-600">
                    {{ form.errors.title }}
                </div>
            </el-form-item>
            <el-form-item label="Desc" size="large">
                <el-input
                    v-model="form.desc"
                    type="textarea"
                    rows="5"
                    placeholder=""
                />
                <div v-if="form.errors.desc" class="text-red-600">
                    {{ form.errors.desc }}
                </div>
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
import { router, useForm } from "@inertiajs/vue3";
import { ElMessage } from "element-plus";
export default {
    props: ["show", "title", "data", "faq_types"],
    emits: ["closed"],
    setup(props, context) {
        const state = reactive({
            dialogTitle: "",
            doubleClick: false,
        });

        const formRef = ref();
        const form = useForm({
            title: "",
            desc: "",
            faq_type: "",
        });

        const submitDialog = (formRef) => {
            formRef.validate((valid) => {
                if (valid) {
                    if (state.dialogTitle === "Create") {
                        state.doubleClick = true;
                        form.post(route("admin.faqs.store"), {
                            onSuccess: (page) => {
                                state.doubleClick = false;
                                closeDialog(formRef);
                                ElMessage.success(page.props.flash.success);
                            },
                            onError: () => {
                                state.doubleClick = false;
                            },
                        });
                    } else {
                        state.doubleClick = true;
                        form.patch(route("admin.faqs.update", props.data.id), {
                            onSuccess: (page) => {
                                state.doubleClick = false;
                                closeDialog(formRef);
                                ElMessage.success(page.props.flash.success);
                            },
                            onError: () => {
                                state.doubleClick = false;
                            },
                        });
                    }
                }
            });
        };

        const closeDialog = (formRef) => {
            form.reset();
            formRef.resetFields();
            context.emit("closed");
        };

        const openDialog = () => {
            state.dialogTitle = props.title;
            form.title = props.data.title ?? "";
            form.desc = props.data.desc ?? "";
            form.faq_type = props.data.faq_type_id ?? "";
        };

        return {
            ...toRefs(state),
            openDialog,
            formRef,
            closeDialog,
            submitDialog,
            form,
        };
    },
};
</script>

<style></style>
