<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <AlertButton
                    v-if="changeAlert.files"
                    title="Files Updated.  Refresh for most recent data"
                />
                <RefreshButton
                    :only="['files']"
                    @loading-start="toggleLoading('files')"
                    @loading-complete="clearAlert('files')"
                />
                Files:
                <CustomerFileCreate class="float-end" :equipment="equipment" />
            </div>
            <Overlay :loading="loading.files">
                <div v-if="!files.length">
                    <h6 class="text-center">No Files</h6>
                </div>
                <Table
                    v-else
                    :columns="tableColumns"
                    :rows="files"
                    no-inertia-link
                >
                    <template #action="{ rowData }">
                        <span
                            class="badge bg-info rounded-pill pointer"
                            title="Show File Details"
                            v-tooltip
                            @click="showFileDetails(rowData)"
                        >
                            <fa-icon icon="circle-info" />
                        </span>
                        <CustomerFileEdit
                            v-if="permissions.files.update"
                            :customer-file="rowData"
                        />
                        <DeleteBadge
                            v-if="permissions.files.delete"
                            @click="deleteFile(rowData)"
                        />
                    </template>
                </Table>
            </Overlay>
        </div>
        <CustomerFileDetails
            v-if="showDetailsModal"
            ref="customerFileDetails"
        />
    </div>
</template>

<script setup lang="ts">
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import AlertButton from "../_Base/Buttons/AlertButton.vue";
import Overlay from "../_Base/Loaders/Overlay.vue";
import Table from "../_Base/Table.vue";
import CustomerFileCreate from "@/Components/Customer/CustomerFileCreate.vue";
import CustomerFileEdit from "@/Components/Customer/CustomerFileEdit.vue";
import CustomerFileDetails from "@/Components/Customer/CustomerFileDetails.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import { nextTick, reactive, ref } from "vue";
import {
    loading,
    toggleLoading,
    files,
    permissions,
    customer,
    changeAlert,
    clearAlert,
} from "@/State/CustomerState";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    equipment?: customerEquipment;
}>();

const customerFileDetails = ref<InstanceType<
    typeof CustomerFileDetails
> | null>(null);
const showDetailsModal = ref<boolean>(false);
const showFileDetails = (fileData: customerFile) => {
    showDetailsModal.value = true;
    nextTick(() => customerFileDetails.value?.show(fileData));
};

const deleteFile = (fileData: customerFile) => {
    verifyModal("Do you want to delete this file?").then((res) => {
        if (res) {
            toggleLoading("files");
            router.delete(
                route("customers.files.destroy", [
                    customer.value.cust_id,
                    fileData.cust_file_id,
                ]),
                {
                    preserveScroll: true,
                    onFinish: () => toggleLoading("files"),
                }
            );
        }
    });
};

const tableColumns = reactive([
    {
        label: "Name",
        field: "name",
        sort: true,
        filterOptions: {
            enabled: true,
        },
    },
    {
        label: "Type",
        field: "file_type",
        sort: true,
        filterOptions: {
            enabled: true,
        },
    },
    {
        label: "Uploaded On",
        field: "created_at",
        sort: true,
        // sortField: "created_stamp",
        filterOptions: {
            enabled: true,
        },
    },
]);
</script>
