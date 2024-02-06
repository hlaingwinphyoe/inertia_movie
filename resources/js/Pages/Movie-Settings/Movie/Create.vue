<template>
    <AuthenticatedLayout>
        <div class="text-white pl-0 p-2">
            <div class="flex justify-between items-center">
                <h4 class="text-xl font-bold" v-if="title === 'Create'">
                    Add New Movie
                </h4>
                <h4 class="text-xl font-bold" v-else>Edit Movie</h4>
                <!-- Bradcrumb -->
                <Breadcrumb>
                    <el-breadcrumb-item>
                        <Link :href="route('admin.movies.index')"
                            >Movie Lists
                        </Link>
                    </el-breadcrumb-item>
                    <el-breadcrumb-item>{{ title }}</el-breadcrumb-item>
                </Breadcrumb>
            </div>
            <el-form
                label-width="120px"
                ref="formRef"
                :model="form"
                label-position="top"
            >
                <div class="grid grid-cols-4 items-center gap-4">
                    <div class="col-span-1">
                        <el-form-item
                            label="Cover Photo"
                            size="large"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Cover Photo is required',
                                    trigger: 'blur',
                                },
                            ]"
                        >
                            <div
                                class="p-3 border border-[#4C4D4F] h-80 w-full rounded flex items-center justify-center"
                            >
                                <h4>Upload Cover (270 x 400)</h4>
                            </div>
                        </el-form-item>
                    </div>
                    <div class="col-span-3">
                        <el-form-item
                            label="Title"
                            size="large"
                            prop="title"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Title is required',
                                    trigger: 'blur',
                                },
                            ]"
                        >
                            <el-input v-model="form.title" placeholder="" />
                        </el-form-item>
                        <el-form-item
                            label="Description"
                            size="large"
                            prop="description"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Description is required',
                                    trigger: 'blur',
                                },
                            ]"
                        >
                            <el-input
                                v-model="form.description"
                                type="textarea"
                                :rows="6"
                                placeholder=""
                            />
                        </el-form-item>
                        <div class="grid grid-cols-3 gap-4">
                            <el-form-item
                                label="Director"
                                size="large"
                                prop="director"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Director is required',
                                        trigger: 'blur',
                                    },
                                ]"
                            >
                                <el-input
                                    v-model="form.director"
                                    placeholder=""
                                />
                            </el-form-item>
                            <el-form-item
                                class="col-span-2"
                                label="Casts"
                                size="large"
                                prop="casts"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Casts is required',
                                        trigger: 'blur',
                                    },
                                ]"
                            >
                                <el-input v-model="form.casts" placeholder="" />
                            </el-form-item>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <el-form-item
                        label="Release Year"
                        size="large"
                        prop="release_year"
                        :rules="[
                            {
                                required: true,
                                message: 'Release Year is required',
                                trigger: 'blur',
                            },
                        ]"
                    >
                        <el-input v-model="form.release_year" placeholder="" />
                    </el-form-item>
                    <el-form-item
                        label="Running Time in Minutes"
                        size="large"
                        :rules="[
                            {
                                required: true,
                                message: 'Running Time is required',
                                trigger: 'blur',
                            },
                        ]"
                    >
                        <el-input v-model="form.running_time" placeholder="" />
                    </el-form-item>
                    <el-form-item
                        label="Country"
                        size="large"
                        :rules="[
                            {
                                required: true,
                                message: 'Country is required',
                                trigger: 'blur',
                            },
                        ]"
                    >
                        <el-select
                            v-model="form.country_id"
                            placeholder="Select"
                            size="large"
                        >
                            <el-option
                                v-for="item in countries"
                                :key="item.slug"
                                :label="item.name"
                                :value="item.slug"
                            />
                        </el-select>
                    </el-form-item>
                    <el-form-item
                        label="Video Quality"
                        size="large"
                        :rules="[
                            {
                                required: true,
                                message: 'Video Quality is required',
                                trigger: 'blur',
                            },
                        ]"
                    >
                        <el-select
                            v-model="form.video_quality"
                            placeholder="Select"
                            size="large"
                        >
                            <el-option
                                v-for="item in videoQuality"
                                :key="item"
                                :label="item"
                                :value="item"
                            />
                        </el-select>
                    </el-form-item>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-5">
                    <el-form-item
                        class="col-span-1"
                        label="Movie Type"
                        size="large"
                        :rules="[
                            {
                                required: true,
                                message: 'Type is required',
                                trigger: 'blur',
                            },
                        ]"
                    >
                        <el-radio-group v-model="form.type_id">
                            <el-radio-button
                                v-for="item in types"
                                :key="item.id"
                                :label="item.name"
                            />
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item
                        class="col-span-2"
                        label="Category"
                        size="large"
                        :rules="[
                            {
                                required: true,
                                message: 'Category is required',
                                trigger: 'blur',
                            },
                        ]"
                    >
                        <el-select
                            v-model="form.category_id"
                            multiple
                            collapse-tags
                            collapse-tags-tooltip
                            :max-collapse-tags="3"
                            placeholder="Select"
                            size="large"
                        >
                            <el-option
                                v-for="item in categories"
                                :key="item.slug"
                                :label="item.name"
                                :value="item.slug"
                            />
                        </el-select>
                    </el-form-item>
                </div>
                <div class="pt-5 border-t border-[#4C4D4F]">
                    <el-button>Save as draft</el-button>
                    <el-button class="app-button">Publish</el-button>
                </div>
            </el-form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { reactive, ref, toRefs } from "vue";
export default {
    props: ["title", "categories", "types", "countries"],
    components: { AuthenticatedLayout, Link, Breadcrumb },
    setup() {
        const state = reactive({
            form: {},
            isLoading: false,
            videoQuality: ["HD", "Full HD"],
        });

        const formRef = ref();

        return {
            ...toRefs(state),
            formRef,
        };
    },
};
</script>

<style></style>
