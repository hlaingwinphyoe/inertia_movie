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
                <div class="grid grid-cols-1 lg:grid-cols-4 items-center gap-4">
                    <div class="col-span-1">
                        <el-form-item label="Cover Photo" size="large">
                            <div
                                class="border border-secondary-500 h-80 w-full rounded flex items-center justify-center cursor-pointer overflow-hidden"
                                @click="selectImage"
                            >
                                <img
                                    v-show="imgSrc"
                                    id="preview_img"
                                    class="w-full h-auto object-cover"
                                    :src="imgSrc"
                                />
                                <h4 v-show="!imgSrc">
                                    Upload Cover (270 x 400)
                                </h4>

                                <input
                                    type="file"
                                    class="hidden"
                                    name="image"
                                    id="upload"
                                    @change="loadFile"
                                    accept="image/*"
                                />
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
                                label="Genre"
                                size="large"
                                :rules="[
                                    {
                                        required: true,
                                        message: 'Genre is required',
                                        trigger: 'blur',
                                    },
                                ]"
                            >
                                <el-select
                                    v-model="form.genre_id"
                                    multiple
                                    collapse-tags
                                    collapse-tags-tooltip
                                    :max-collapse-tags="3"
                                    placeholder="Select"
                                    size="large"
                                >
                                    <el-option
                                        v-for="item in genres"
                                        :key="item.slug"
                                        :label="item.name"
                                        :value="item.id"
                                    />
                                </el-select>
                            </el-form-item>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <el-form-item
                        label="Release Date"
                        size="large"
                        prop="release_date"
                        :rules="[
                            {
                                required: true,
                                message: 'Release Date is required',
                                trigger: 'blur',
                            },
                        ]"
                    >
                        <el-date-picker
                            v-model="form.release_date"
                            type="date"
                            placeholder="Pick release date"
                            format="DD/MM/YYYY"
                            value-format="YYYY-MM-DD"
                        />
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
                                :value="item.id"
                            />
                        </el-select>
                    </el-form-item>
                    <div class="grid grid-cols-2 gap-4">
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
                        <el-form-item
                            label="Rating"
                            size="large"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Rating is required',
                                    trigger: 'blur',
                                },
                            ]"
                        >
                            <el-input-number
                                v-model="form.rating"
                                :min="1"
                                :max="10"
                                controls-position="right"
                                step="0.5"
                            />
                        </el-form-item>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <el-form-item
                        label="Trailer Video (Embed Html)"
                        size="large"
                    >
                        <el-input
                            v-model="form.trailer_video"
                            placeholder=""
                            class="mb-3"
                        />

                        <el-button
                            v-if="embed_html"
                            type="danger"
                            plain
                            size="default"
                            @click="dialogVisible = true"
                        >
                            Play Trailer
                        </el-button>

                        <el-dialog
                            v-model="dialogVisible"
                            title="Trailer Video"
                            draggable
                        >
                            <div
                                class="aspect-w-16 aspect-h-9"
                                v-html="embed_html"
                            ></div>
                        </el-dialog>

                        <!-- <input
                            ref="videoRef"
                            @change="uploadVideo"
                            class="block w-full text-xs border rounded cursor-pointer text-gray-400 focus:outline-none bg-transparent border-secondary-500 placeholder-secondary-800"
                            accept="video/*"
                            type="file"
                        />

                        <div class="mt-4" v-if="videoSrc">
                            <video
                                width="340"
                                height="240"
                                controls
                                :src="videoSrc"
                                type="video/mp4"
                            ></video>
                        </div> -->
                    </el-form-item>
                    <el-form-item label="Photos" size="large">
                        <el-upload
                            v-model:file-list="fileList"
                            list-type="picture-card"
                            multiple
                            :on-remove="handleRemove"
                            :on-change="handleFileChange"
                            accept="image/*"
                            :limit="10"
                            :auto-upload="false"
                        >
                            <font-awesome-icon :icon="['fas', 'plus']" />
                        </el-upload>
                    </el-form-item>
                </div>
                <div class="pt-5 border-t border-[#4C4D4F]">
                    <el-button type="primary" @click="submitForm(formRef)">
                        <font-awesome-icon :icon="['fas', 'floppy-disk']" />
                        <span v-if="title == 'Create'" class="ml-1.5"
                            >Publish</span
                        >
                        <span v-else class="ml-1.5">Update</span>
                    </el-button>
                </div>
            </el-form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { reactive, ref, toRefs } from "vue";
import { ElMessage } from "element-plus";
export default {
    props: ["title", "genres", "types", "countries", "movie"],
    components: { AuthenticatedLayout, Link, Breadcrumb },
    setup(props) {
        const state = reactive({
            form: {
                title: props.movie ? props.movie.title : "",
                description: props.movie ? props.movie.description : "",
                director: props.movie ? props.movie.director : "",
                release_date: props.movie ? props.movie.release_date : "",
                running_time: props.movie ? props.movie.running_time : "",
                country_id: props.movie ? props.movie.country_id : "",
                // type_id: props.movie ? props.movie.type_id : "",
                video_quality: props.movie ? props.movie.video_quality : "",
                genre_id: props.movie
                    ? props.movie.genres.map((a) => a.id)
                    : [],
                rating: props.movie ? props.movie.rating : 1,
                trailer_video: "",
            },
            virtualForm: new FormData(),
            isLoading: false,
            videoQuality: ["HD", "Full HD"],
            imgSrc: props.movie ? props.movie.thumbnail : "",
            dialogVisible: false,
            // videoSrc: props.movie ? props.movie.video : "",
            embed_html: props.movie ? props.movie.trailer_video : "",
            fileList: props.movie ? props.movie.medias : [],
        });

        const formRef = ref();

        const handleFileChange = (file) => {
            state.fileList.push(file);
        };

        const submitForm = (formRef) => {
            state.isLoading = true;
            formRef.validate((valid) => {
                if (valid) {
                    state.virtualForm.append("title", state.form.title);
                    for (var i = 0; i < state.form.genre_id.length; i++) {
                        state.virtualForm.append(
                            "genres[]",
                            state.form.genre_id[i]
                        );
                    }
                    state.virtualForm.append(
                        "country_id",
                        state.form.country_id
                    );
                    state.virtualForm.append(
                        "description",
                        state.form.description
                    );
                    state.virtualForm.append("director", state.form.director);
                    state.virtualForm.append(
                        "release_date",
                        state.form.release_date
                    );
                    state.virtualForm.append(
                        "running_time",
                        state.form.running_time
                    );
                    // state.virtualForm.append("type_id", state.form.type_id);
                    state.virtualForm.append(
                        "video_quality",
                        state.form.video_quality
                    );

                    state.virtualForm.append("rating", state.form.rating);
                    state.virtualForm.append(
                        "trailer_video",
                        state.form.trailer_video
                    );

                    for (const image of state.fileList) {
                        state.virtualForm.append("movie_images[]", image.raw);
                    }

                    if (props.title === "Create") {
                        router.post(
                            route("admin.movies.store"),
                            state.virtualForm,
                            {
                                onSuccess: (page) => {
                                    state.isLoading = false;
                                    ElMessage.success(page.props.flash.success);
                                    formRef.resetFields();
                                },
                                onError: () => {
                                    state.isLoading = false;
                                    formRef.resetFields();
                                },
                            }
                        );
                    } else {
                        state.virtualForm.append("_method", "patch");
                        router.post(
                            route("admin.movies.update", props.movie.id),
                            state.virtualForm,
                            {
                                onSuccess: (page) => {
                                    state.isLoading = false;
                                    ElMessage.success(page.props.flash.success);
                                    formRef.resetFields();
                                },
                                onError: (page) => {
                                    state.isLoading = false;
                                    formRef.resetFields();
                                    ElMessage.error(page.error);
                                },
                            }
                        );
                    }
                }
            });
        };

        // upload Image
        const selectImage = () => {
            var upload = document.getElementById("upload");
            upload.click();
        };

        // const uploadVideo = () => {
        //     state.virtualForm.append("video", videoRef.value.files[0]);
        // };

        const loadFile = (event) => {
            var input = event.target;
            var file = input.files[0];

            state.imgSrc = URL.createObjectURL(file);

            state.virtualForm.append("cover", file);

            var output = document.getElementById("preview_img");
            output.src = URL.createObjectURL(file);
            output.onload = function () {
                URL.revokeObjectURL(output.src); // free memory
            };
        };

        const handleRemove = (uploadFile) => {
            router.delete(route("admin.movies.destroyMedia", uploadFile.id), {
                onSuccess: (page) => {
                    ElMessage.success(page.props.flash.success);
                },
            });
        };

        return {
            ...toRefs(state),
            formRef,
            loadFile,
            selectImage,
            submitForm,
            handleFileChange,
            handleRemove,
        };
    },
};
</script>

<style></style>
