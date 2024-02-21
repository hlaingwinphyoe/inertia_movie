<template>
    <el-dialog
        :modelValue="show"
        @update:modelValue="show = $event"
        @close="closeDialog(formRef)"
        draggable
        :close-on-click-modal="false"
        width="30%"
        center
    >
        <div class="text-center mb-6">
            <h4 class="text-xl uppercase">Movie</h4>
        </div>
        <div class="px-4">
            <el-form
                label-width="100px"
                ref="formRef"
                :model="form"
                label-position="top"
            >
                <el-form-item
                    label="Email"
                    size="large"
                    :rules="[
                        {
                            required: true,
                            message: 'Email is required',
                            trigger: 'blur',
                        },
                    ]"
                >
                    <el-input
                        type="email"
                        v-model="form.email"
                        placeholder=""
                    />
                    <div v-if="form.errors.email" class="text-red-600">
                        {{ form.errors.email }}
                    </div>
                </el-form-item>
                <el-form-item
                    label="Password"
                    size="large"
                    :rules="[
                        {
                            required: true,
                            message: 'Password is required',
                            trigger: 'blur',
                        },
                    ]"
                >
                    <el-input
                        type="password"
                        v-model="form.password"
                        placeholder=""
                    />
                    <div v-if="form.errors.password" class="text-red-600">
                        {{ form.errors.password }}
                    </div>
                </el-form-item>

                <div class="flex justify-between items-center">
                    <el-checkbox
                        v-model="form.remember"
                        label="Remember Me"
                        size="large"
                        name="remember"
                    />
                    <Link
                        :href="route('password.request')"
                        class="underline text-sm text-gray-400 hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Forgot your password?
                    </Link>
                </div>
            </el-form>
        </div>
        <template #footer>
            <div class="px-4 pb-4">
                <el-button
                    type="primary"
                    class="w-full"
                    size="large"
                    @click="submitDialog(formRef)"
                >
                    Login
                </el-button>
            </div>
            <span class="text-gray-400">or</span>
            <div class="py-4">
                <el-button size="large">
                    <font-awesome-icon :icon="['fab', 'google']" />
                </el-button>
                <el-button size="large">
                    <font-awesome-icon :icon="['fab', 'facebook']" />
                </el-button>
                <el-button size="large">
                    <font-awesome-icon :icon="['fab', 'x-twitter']" />
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import { reactive, ref, toRefs } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ElMessage } from "element-plus";
export default {
    props: ["show"],
    emits: ["closed"],
    setup(props, context) {
        const state = reactive({
            doubleClick: false,
        });
        const formRef = ref();
        const form = useForm({
            email: "",
            password: "",
            remember: false,
        });
        const submitDialog = (formRef) => {
            formRef.validate((valid) => {
                if (valid) {
                    form.post(route("login"), {
                        onSuccess: () => closeDialog(formRef),
                    });
                }
            });
        };
        const closeDialog = (formRef) => {
            form.reset();
            formRef.resetFields();
            context.emit("closed");
        };
        return {
            ...toRefs(state),
            formRef,
            closeDialog,
            submitDialog,
            form,
        };
    },
    components: { Link },
};
</script>

<style></style>
