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
                <Link :href="route('admin.movies.create')">
                    <el-button class="app-button">
                        <font-awesome-icon icon="fa-solid fa-plus" />
                        Add New
                    </el-button>
                </Link>

                <div>
                    <el-input placeholder="Search..." size="large" />
                </div>
            </div>

            <!-- Main -->

            <div class="relative overflow-x-auto">
                <table
                    class="w-full border-y border-gray-800 text-sm text-left text-gray-400"
                >
                    <thead
                        class="text-xs uppercase text-gray-200 bg-secondary-600"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3.5">#</th>
                            <th scope="col" class="px-6 py-3.5">Cover</th>
                            <th scope="col" class="px-6 py-3.5">Title</th>
                            <th scope="col" class="px-6 py-3.5">Rating</th>
                            <th scope="col" class="px-6 py-3.5">Category</th>
                            <th scope="col" class="px-6 py-3.5">Type</th>
                            <th scope="col" class="px-6 py-3.5">Views</th>
                            <th scope="col" class="px-6 py-3.5">Published</th>
                            <th scope="col" class="px-6 py-3.5">Created</th>
                            <th scope="col" class="px-6 py-3.5">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="border-b border-secondary-700"
                            v-for="row in movies.data"
                            :key="row.id"
                        >
                            <td class="px-6 py-3.5">{{ row.id }}</td>
                            <td class="px-6 py-3.5">
                                <img
                                    v-if="row.thumbnail"
                                    :src="row.thumbnail"
                                    class="w-auto h-10"
                                    alt=""
                                />
                            </td>
                            <td class="px-6 py-3.5">{{ row.title }}</td>
                            <td class="px-6 py-3.5">
                                <font-awesome-icon
                                    :icon="['fas', 'star']"
                                    size="xs"
                                    class="text-yellow-400"
                                />
                                {{ row.rating }}
                            </td>
                            <td class="px-6 py-3.5">{{ row.categories }}</td>
                            <td class="px-6 py-3.5">{{ row.type }}</td>
                            <td class="px-6 py-3.5">{{ row.views }}</td>
                            <td class="px-6 py-3.5">
                                <el-switch
                                    v-model="row.is_published"
                                    :active-value="1"
                                    :inactive-value="0"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949"
                                    @change="changeStatus(row)"
                                />
                            </td>
                            <td class="px-6 py-3.5">
                                {{ row.created_at }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <el-tooltip
                                    class="box-item"
                                    content="Edit"
                                    placement="top"
                                >
                                    <el-button
                                        type="warning"
                                        style="margin-bottom: 5px"
                                        circle
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'admin.movies.edit',
                                                    row.id
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
                                        @click="deleteHandler(row.id)"
                                    >
                                        <font-awesome-icon
                                            :icon="['fas', 'trash-can']"
                                        />
                                    </el-button>
                                </el-tooltip>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <Pagination :links="movies.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import Breadcrumb from "@/Components/Breadcrumb.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, toRefs } from "vue";
import { Link, router } from "@inertiajs/vue3";
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
        };
    },
};
</script>
