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
                label="Casts"
                prop="casts"
                size="large"
                :rules="[
                    {
                        required: true,
                        message: 'Casts is required',
                        trigger: 'blur',
                    },
                ]"
            >
                <el-select
                    v-model="form.casts"
                    filterable
                    multiple
                    placeholder="Select Casts"
                >
                    <el-option
                        v-for="item in casts"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                    >
                        <div class="flex items-center gap-2">
                            <el-image
                                class="h-8 w-8 rounded-full"
                                :src="item.profile"
                                fit="cover"
                            />
                            <span>{{ item.name }}</span>
                        </div>
                    </el-option>
                </el-select>
                <div v-if="form.errors.casts" class="text-red-600">
                    {{ form.errors.casts }}
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
import { useForm } from "@inertiajs/vue3";
import { ElMessage } from "element-plus";
export default {
    props: ["show", "title", "data", "casts"],
    emits: ["closed"],
    setup(props, context) {
        const state = reactive({
            dialogTitle: "",
            doubleClick: false,
            movieTitle: "",
        });

        const formRef = ref();

        const form = useForm({
            casts: [],
        });

        const submitDialog = (formRef) => {
            formRef.validate((valid) => {
                if (valid) {
                    form.post(
                        route("admin.movies.attach-casts", props.data.id),
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
            });
        };

        const closeDialog = (formRef) => {
            form.reset();
            formRef.resetFields();
            context.emit("closed");
        };

        const openDialog = () => {
            state.dialogTitle = props.title;

            state.movieTitle = props.data.title;
            form.movieId = props.data.id;
            form.casts = props.data.casts
                ? props.data.casts.map((a) => a.id)
                : [];
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
