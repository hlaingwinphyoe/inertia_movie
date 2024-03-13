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
            <h4 class="text-xl uppercase">Login</h4>
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
            <div class="p-4">
                <a
                    href="/auth/google/redirect"
                    class="text-white bg-secondary-700 hover:bg-secondary-700/90 focus:ring-4 focus:outline-none focus:ring-secondary-700/50 font-medium rounded-md text-sm px-5 py-2.5 text-center inline-flex items-center justify-center mb-2 w-full"
                >
                    <font-awesome-icon :icon="['fab', 'google']" class="mr-1" />
                    Sign in with Google
                </a>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import { reactive, ref, toRefs } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
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
                        onFinish: () => window.location.reload(),
                    });
                }
            });
        };
        const closeDialog = (formRef) => {
            form.reset();
            formRef.resetFields();
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
