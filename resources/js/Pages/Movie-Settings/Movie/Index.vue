<template>
    <AuthenticatedLayout>
        <div class="text-white pl-0 p-2">
            <div class="flex justify-between items-center">
                <h4 class="text-xl font-bold">
                    Movies
                    <small class="ml-2 text-gray-500 font-thin text-[13px]"
                        >{{ total }} Total</small
                    >
                </h4>
                <!-- Bradcrumb -->
                <Breadcrumb>
                    <el-breadcrumb-item>Movies</el-breadcrumb-item>
                </Breadcrumb>
            </div>

            <!-- Form -->
            <div class="flex items-center justify-between my-4">
                <div class="flex items-center">
                    <Link :href="route('admin.movies.create')">
                        <el-button type="primary">
                            <font-awesome-icon icon="fa-solid fa-plus" />
                            Add New
                        </el-button>
                    </Link>
                    <div class="flex gap-2 ml-4">
                        <el-input
                            v-model="form.movieId"
                            placeholder="TMDB Movie ID"
                        />
                        <el-button
                            type="success"
                            :disabled="form.movieId === '' || form.processing"
                            @click="generateItem"
                        >
                            <font-awesome-icon :icon="['fas', 'server']" />
                            <span class="ml-1">Generate</span>
                        </el-button>
                    </div>
                </div>
                <div>
                    <el-input placeholder="Search..." size="large" />
                </div>
            </div>

            <!-- Main -->

            <div class="relative overflow-x-auto">
                <el-table
                    :data="movies.data"
                    :default-sort="{ prop: 'name', order: 'ascending' }"
                    table-layout="fixed"
                >
                    <el-table-column type="index" label="Sr." width="50" />
                    <el-table-column label="Rating" align="center">
                        <template #default="scope">
                            <el-image
                                class="w-8 h-10 rounded"
                                :src="scope.row.thumbnail"
                                fit="cover"
                            />
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="title"
                        label="Title"
                        sortable
                        align="center"
                    />

                    <el-table-column label="Rating" align="center">
                        <template #default="scope">
                            <font-awesome-icon
                                :icon="['fas', 'star']"
                                class="text-yellow-300 mr-1 text-xs"
                            />
                            <span>{{ scope.row.rating }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column
                        prop="genres"
                        label="Genres"
                        align="center"
                    />

                    <el-table-column
                        prop="views"
                        label="Views"
                        align="center"
                    />

                    <el-table-column
                        prop="created_at"
                        label="Created At"
                        sortable
                        align="center"
                    />
                    <el-table-column label="Actions" align="center">
                        <template #default="scope">
                            <el-tooltip
                                class="box-item"
                                content="Edit"
                                placement="top"
                            >
                                <el-button
                                    type="warning"
                                    circle
                                    style="margin-bottom: 5px"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.movies.edit',
                                                scope.row.id
                                            )
                                        "
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
                                    </Link>
                                </el-button>
                            </el-tooltip>
                            <el-tooltip
                                class="box-item"
                                content="Delete"
                                placement="top"
                            >
                                <el-button
                                    type="danger"
                                    circle
                                    style="margin-bottom: 5px"
                                    @click="deleteHandler(scope.row.id)"
                                >
                                    <font-awesome-icon
                                        :icon="['fas', 'trash-can']"
                                    />
                                </el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>

                <Pagination :links="movies.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import Breadcrumb from "@/Components/Breadcrumb.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, toRefs } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { ElMessage, ElMessageBox } from "element-plus";
import Pagination from "@/Shared/Pagination.vue";
export default {
    props: ["movies", "filters"],
    components: {
        Breadcrumb,
        AuthenticatedLayout,
        Pagination,
        Link,
    },
    setup(props) {
        const state = reactive({
            showDialog: false,
            isLoading: false,
            total: props.movies.total,
            search: props.filters ?? "",
        });

        const form = useForm({
            movieId: "",
        });

        const generateItem = () => {
            form.post(route("admin.movies.generate"), {
                onSuccess: (page) => {
                    form.reset();
                    ElMessage.success(page.props.flash.success);
                },
            });
        };

        const changeStatus = (row) => {
            ElMessageBox.confirm(
                "Are you sure want to change status of this movie?",
                "Confirmation",
                {
                    confirmButtonText: "Confirm",
                    cancelButtonText: "Cancel",
                    type: "warning",
                    draggable: true,
                    closeOnClickModal: false,
                }
            )
                .then(() => {
                    router.patch(
                        route("admin.movies.change-status", row.id),
                        {},
                        {
                            preserveState: true,
                            onSuccess: (page) => {
                                ElMessage.success(page.props.flash.success);
                            },
                            onError: (page) => {
                                ElMessage.error(page.props.flash.error);
                            },
                        }
                    );
                })
                .catch(() => {
                    router.reload();
                    ElMessage({
                        type: "info",
                        message: "Cancel",
                    });
                });
        };

        const deleteHandler = (id) => {
            ElMessageBox.confirm(
                "Are you sure you want to delete?",
                "Warning",
                {
                    confirmButtonText: "Confirm",
                    cancelButtonText: "Cancel",
                    type: "warning",
                    draggable: true,
                    closeOnClickModal: false,
                }
            )
                .then(() => {
                    router.delete(route("admin.movies.destroy", id), {
                        onSuccess: (page) => {
                            ElMessage.success(page.props.flash.success);
                        },
                        onError: (page) => {
                            ElMessage.error(page.props.flash.error);
                        },
                    });
                })
                .catch(() => {
                    ElMessage({
                        type: "info",
                        message: "Cancel",
                    });
                });
        };

        const closeDialog = () => {
            state.showDialog = false;
        };

        return {
            ...toRefs(state),
            closeDialog,
            deleteHandler,
            changeStatus,
            generateItem,
            form,
        };
    },
};
</script>
