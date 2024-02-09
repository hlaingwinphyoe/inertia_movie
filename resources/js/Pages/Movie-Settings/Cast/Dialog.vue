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
            <div class="flex gap-6 items-center">
                <div class="">
                    <el-form-item label="Cover Photo" size="large">
                        <div
                            class="border border-secondary-500 h-32 w-32 rounded flex items-center justify-center cursor-pointer overflow-hidden"
                            @click="selectImage"
                        >
                            <img
                                v-show="imgSrc"
                                id="preview_img"
                                class="w-full h-auto object-cover"
                                :src="imgSrc"
                            />
                            <h4 v-show="!imgSrc">Upload Profile</h4>

                            <input
                                type="file"
                                class="hidden"
                                name="image"
                                id="upload"
                                @change="loadFile"
                                accept="image/*"
                            />
                        </div>
                        <div v-if="form.errors.profile" class="text-red-600">
                            {{ form.errors.profile }}
                        </div>
                    </el-form-item>
                </div>
                <div class="w-full">
                    <el-form-item
                        label="Name"
                        size="large"
                        prop="name"
                        :rules="[
                            {
                                required: true,
                                message: 'Name is required',
                                trigger: 'blur',
                            },
                        ]"
                    >
                        <el-input v-model="form.name" placeholder="" />
                        <div v-if="form.errors.name" class="text-red-600">
                            {{ form.errors.name }}
                        </div>
                    </el-form-item>
                    <div class="flex gap-4">
                        <el-form-item
                            label="Birthday"
                            size="large"
                            prop="birthday"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Birthday is required',
                                    trigger: 'blur',
                                },
                            ]"
                        >
                            <el-date-picker
                                v-model="form.birthday"
                                type="date"
                                placeholder="Select Birthday"
                                format="YYYY/MM/DD"
                                value-format="YYYY-MM-DD"
                            />
                            <div
                                v-if="form.errors.birthday"
                                class="text-red-600"
                            >
                                {{ form.errors.birthday }}
                            </div>
                        </el-form-item>
                        <el-form-item
                            label="Gender"
                            size="large"
                            prop="gender"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Gender is required',
                                    trigger: 'blur',
                                },
                            ]"
                        >
                            <el-radio-group v-model="form.gender">
                                <el-radio-button
                                    v-for="item in genders"
                                    :key="item"
                                    :label="item"
                                    >{{ item }}
                                </el-radio-button>
                            </el-radio-group>
                            <div v-if="form.errors.gender" class="text-red-600">
                                {{ form.errors.gender }}
                            </div>
                        </el-form-item>
                    </div>
                </div>
            </div>

            <el-form-item label="Biography" size="large">
                <el-input
                    v-model="form.biography"
                    type="textarea"
                    rows="5"
                    placeholder=""
                />
                <div v-if="form.errors.biography" class="text-red-600">
                    {{ form.errors.biography }}
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
    props: ["show", "title", "data"],
    emits: ["closed"],
    setup(props, context) {
        const state = reactive({
            dialogTitle: "",
            doubleClick: false,
            genders: ["Male", "Female", "Other"],
            imgSrc: "",
        });

        const formRef = ref();
        const form = useForm({
            name: "",
            profile: "",
            gender: "",
            biography: "",
            birthday: "",
        });

        const submitDialog = (formRef) => {
            formRef.validate((valid) => {
                if (valid) {
                    if (state.dialogTitle === "Create") {
                        state.doubleClick = true;
                        form.post(route("admin.casts.store"), {
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
                        router.post(
                            route("admin.casts.update", props.data.id),
                            {
                                _method: "patch",
                                name: form.name,
                                profile: form.profile,
                                gender: form.gender,
                                biography: form.biography,
                                birthday: form.birthday,
                            },
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
            form.reset();
            formRef.resetFields();
            context.emit("closed");
        };

        const openDialog = () => {
            state.dialogTitle = props.title;
            form.name = props.data.name ?? "";
            form.birthday = props.data.birthday ?? "";
            form.gender = props.data.gender ?? "";
            form.biography = props.data.biography ?? "";
            state.imgSrc = props.data.profile ?? "";
        };

        const selectImage = () => {
            var upload = document.getElementById("upload");
            upload.click();
        };

        const loadFile = (event) => {
            var input = event.target;
            var file = input.files[0];

            state.imgSrc = URL.createObjectURL(file);

            form.profile = file;

            var output = document.getElementById("preview_img");
            output.src = URL.createObjectURL(file);
            output.onload = function () {
                URL.revokeObjectURL(output.src); // free memory
            };
        };

        return {
            ...toRefs(state),
            openDialog,
            formRef,
            closeDialog,
            submitDialog,
            form,
            selectImage,
            loadFile,
        };
    },
};
</script>

<style></style>
