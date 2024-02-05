<template>
    <AuthenticatedLayout>
        <div class="text-white pl-0 p-2">
            <div class="flex justify-between items-center">
                <h4 class="text-lg font-bold mb-4">Categories</h4>
                <!-- Bradcrumb -->
                <Breadcrumb>
                    <el-breadcrumb-item
                        ><a href="/">Movie Lists</a></el-breadcrumb-item
                    >
                    <el-breadcrumb-item>Categories</el-breadcrumb-item>
                </Breadcrumb>
            </div>

            <!-- Main -->
            <div class="overflow-hidden shadow-sm">
                <div
                    v-loading="isLoading"
                    element-loading-custom-class="border-radius"
                    element-loading-background="rgba(122, 122, 122, 0.3)"
                >
                    <div class="table">
                        <el-button
                            @click="addNew"
                            class="app-button"
                            style="margin-bottom: 25px"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-plus"
                                style="margin-right: 5px"
                            />
                            Add New
                        </el-button>
                    </div>

                    <el-table
                        :data="categories"
                        :row-style="{ height: '40px' }"
                        :header-row-style="{ height: '55px' }"
                        :border="true"
                        v-can="`access-player`"
                    >
                        <el-table-column
                            prop="name"
                            label="Name"
                            align="center"
                        />

                        <el-table-column
                            label="Created"
                            align="center"
                            min-width="110"
                        >
                            <template #default="scope">
                                {{ dateFormat(scope.row.created_at) }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Actions"
                            align="center"
                        >
                            <template #default="scope">
                                <el-tooltip
                                    class="box-item"
                                    content="Edit"
                                    placement="top"
                                >
                                    <el-button
                                        type="warning"
                                        style="margin-bottom: 5px"
                                        circle
                                        @click="handleEdit(scope.row)"
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
                                        @click="deleteHandler(scope.row.id)"
                                    >
                                        <font-awesome-icon
                                            :icon="['far', 'trash-can']"
                                        />
                                    </el-button>
                                </el-tooltip>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
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
import { dateFormat } from "@/utils/date-format.js";
import { router } from "@inertiajs/vue3";
import { ElMessage, ElMessageBox } from "element-plus";
export default {
    props: ["categories"],
    components: {
        Breadcrumb,
        AuthenticatedLayout,
        Dialog,
    },
    setup(props) {
        const state = reactive({
            showDialog: false,
            isLoading: false,
            dialog: {
                dialogTitle: "",
                dialogData: {},
            },
            pageList: [10, 20, 60, 80, 100],
            param: {
                page: 1,
                page_size: 10,
            },
            tableData: props.categories,
            total: 0,
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
            ElMessageBox.confirm("Are you sure you want to delete", "Warning", {
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
                type: "warning",
                draggable: true,
                closeOnClickModal: false,
            })
                .then(() => {
                    router.delete(route("admin.categories.destroy", id), {
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
            dateFormat,
        };
    },
};
</script>
