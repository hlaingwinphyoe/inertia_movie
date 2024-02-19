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
                    <el-input
                        placeholder="Search Movie..."
                        v-model="param.search"
                    />
                </div>
            </div>

            <!-- Main -->

            <div class="relative overflow-x-auto">
                <el-table
                    :data="movies.data"
                    :default-sort="{ prop: 'created_at', order: 'descending' }"
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

                    <el-table-column label="Publish" align="center">
                        <template #default="scope">
                            <el-switch
                                v-model="scope.row.is_published"
                                :active-value="1"
                                :inactive-value="0"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                                @change="changeStatus(scope.row)"
                            />
                        </template>
                    </el-table-column>

                    <el-table-column
                        prop="created_at"
                        label="Created At"
                        sortable
                        align="center"
                    />
                    <el-table-column
                        label="Actions"
                        align="center"
                        fixed="right"
                        width="150"
                    >
                        <template #default="scope">
                            <el-tooltip
                                class="box-item"
                                content="Add Casts"
                                placement="top"
                            >
                                <el-button
                                    type="info"
                                    circle
                                    style="margin-bottom: 5px"
                                    @click="handleAddCasts(scope.row)"
                                >
                                    <font-awesome-icon
                                        :icon="['fas', 'user-plus']"
                                    />
                                </el-button>
                            </el-tooltip>
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

                <div class="my-5 flex items-center justify-center">
                    <el-pagination
                        :hide-on-single-page="total < param.page_size"
                        @size-change="onSizeChange"
                        @current-change="onCurrentChange"
                        :page-size="param.page_size"
                        :background="true"
                        :page-sizes="pageList"
                        :current-page="param.page"
                        :layout="`total,sizes,prev,pager,next,jumper`"
                        :total="total"
                    />
                </div>
            </div>
        </div>

        <AddCastsDialog
            :show="showDialog"
            @closed="closeDialog"
            :title="dialog.dialogTitle"
            :data="dialog.dialogData"
            :casts="casts"
        />
    </AuthenticatedLayout>
</template>

<script>
import Breadcrumb from "@/Components/Breadcrumb.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, toRefs, watch } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { ElMessage, ElMessageBox } from "element-plus";
import AddCastsDialog from "./AddCastsDialog.vue";
import debounce from "lodash.debounce";
export default {
    props: ["movies", "casts"],
    components: {
        Breadcrumb,
        AuthenticatedLayout,
        Link,
        AddCastsDialog,
    },
    setup(props) {
        const state = reactive({
            showDialog: false,
            isLoading: false,
            dialog: {
                dialogTitle: "",
                dialogData: {},
            },
            total: props.movies.total,
            pageList: [10, 20, 60, 80, 100],
            param: {
                page: 1,
                page_size: 10,
                search: "",
            },
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

        watch(
            () => state.param.search,
            debounce(() => {
                getData();
            }, 500)
        );

        const onSizeChange = (val) => {
            state.param.page_size = val;
            getData();
        };

        const onCurrentChange = (val) => {
            state.param.page = val;
            getData();
        };

        function getData() {
            router.get("/admin/movies", state.param, {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            });
        }

        const handleAddCasts = (row) => {
            state.dialog.dialogTitle = "Add Casts To - " + row.title;
            state.dialog.dialogData = JSON.parse(JSON.stringify(row));
            state.showDialog = true;
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
            handleAddCasts,
            onSizeChange,
            onCurrentChange
        };
    },
};
</script>
