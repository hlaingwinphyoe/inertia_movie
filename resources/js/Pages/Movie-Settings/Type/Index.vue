<template>
    <AuthenticatedLayout>
        <div class="text-white pl-0 p-2">
            <div class="flex justify-between items-center">
                <h4 class="text-xl font-bold">
                    Types
                    <small class="ml-2 text-gray-500 font-thin text-[13px]"
                        >{{ total }} Total</small
                    >
                </h4>
                <!-- Bradcrumb -->
                <Breadcrumb>
                    <el-breadcrumb-item
                        ><a href="/">Movie Lists</a></el-breadcrumb-item
                    >
                    <el-breadcrumb-item>Types</el-breadcrumb-item>
                </Breadcrumb>
            </div>

            <!-- Form -->
            <div class="flex items-center justify-between my-4">
                <el-button @click="addNew" class="app-button">
                    <font-awesome-icon icon="fa-solid fa-plus" />
                    Add New
                </el-button>
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
                            <th scope="col" class="px-6 py-3.5">Name</th>
                            <th scope="col" class="px-6 py-3.5">Created</th>
                            <th scope="col" class="px-6 py-3.5">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="border-b border-secondary-700"
                            v-for="row in types.data"
                            :key="row.id"
                        >
                            <td class="px-6 py-3.5">{{ row.id }}</td>
                            <td class="px-6 py-3.5">{{ row.name }}</td>
                            <td class="px-6 py-3.5">
                                {{ row.created_at }}
                            </td>
                            <td class="px-6 py-3.5">
                                <el-tooltip
                                    class="box-item"
                                    content="Edit"
                                    placement="top"
                                >
                                    <el-button
                                        type="warning"
                                        style="margin-bottom: 5px"
                                        circle
                                        @click="handleEdit(row)"
                                    >
                                        <font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                        />
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

                <Pagination :links="types.links" />
            </div>
        </div>

        <Dialog
            :show="showDialog"
            @closed="closeDialog"
            :title="dialog.dialogTitle"
            :data="dialog.dialogData"
        />
    </AuthenticatedLayout>
</template>

<script>
import Breadcrumb from "@/Components/Breadcrumb.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, toRefs } from "vue";
import Dialog from "./Dialog.vue";
import { router } from "@inertiajs/vue3";
import { ElMessage, ElMessageBox } from "element-plus";
import Pagination from "@/Shared/Pagination.vue";
export default {
    props: ["types", "filters"],
    components: {
        Breadcrumb,
        AuthenticatedLayout,
        Dialog,
        Pagination,
    },
    setup(props) {
        const state = reactive({
            showDialog: false,
            isLoading: false,
            dialog: {
                dialogTitle: "",
                dialogData: {},
            },
            total: props.types.total,
            search: props.filters ?? "",
        });

        const addNew = () => {
            state.dialog.dialogTitle = "Create";
            state.dialog.dialogData = {};
            state.showDialog = true;
        };

        const handleEdit = (row) => {
            state.dialog.dialogTitle = "Edit";
            state.dialog.dialogData = JSON.parse(JSON.stringify(row));
            state.showDialog = true;
        };

        const deleteHandler = (id) => {
            ElMessageBox.confirm("Are you sure you want to delete?", "Warning", {
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
                type: "warning",
                draggable: true,
                closeOnClickModal: false,
            })
                .then(() => {
                    router.delete(route("admin.types.destroy", id), {
                        onSuccess: (page) => {
                            ElMessage.success(page.props.flash.success);
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
            addNew,
            closeDialog,
            handleEdit,
            deleteHandler,
        };
    },
};
</script>
