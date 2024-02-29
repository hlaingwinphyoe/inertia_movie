<template>
    <AuthenticatedLayout>
        <div class="text-white pl-0 p-2">
            <div class="flex justify-between items-center">
                <h4 class="text-xl font-bold">Banners</h4>
                <!-- Bradcrumb -->
                <Breadcrumb>
                    <el-breadcrumb-item
                        ><a href="/">Movie Lists</a></el-breadcrumb-item
                    >
                    <el-breadcrumb-item>Banners</el-breadcrumb-item>
                </Breadcrumb>
            </div>

            <!-- Form -->
            <div class="flex items-center justify-between my-4">
                <div class="flex items-center">
                    <el-button @click="addNew" type="primary">
                        <font-awesome-icon icon="fa-solid fa-plus" />
                        <span class="ml-1">Add New</span>
                    </el-button>
                </div>
            </div>

            <!-- Main -->
            <div class="relative overflow-x-auto">
                <el-table :data="banners" table-layout="fixed">
                    <el-table-column type="index" label="Sr." width="100" />
                    <el-table-column label="Image">
                        <template #default="scope">
                            <el-image
                                class="h-10 w-16 rounded-lg border border-secondary-600 shadow-md"
                                :src="scope.row.url"
                                fit="cover"
                            />
                        </template>
                    </el-table-column>
                    <el-table-column prop="name" label="Name" sortable />

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
import { ElMessage, ElMessageBox } from "element-plus";
import { router } from '@inertiajs/vue3';
export default {
    props: ["banners"],
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
        });

        const addNew = () => {
            state.dialog.dialogTitle = "Create";
            state.dialog.dialogData = {};
            state.showDialog = true;
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
                    router.delete(route("admin.banners.destroy", id), {
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
            deleteHandler,
        };
    },
};
</script>
