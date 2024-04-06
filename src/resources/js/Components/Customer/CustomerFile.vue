<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton
                    :only="['files']"
                    @loading-start="toggleLoading('files')"
                    @loading-complete="toggleLoading('files')"
                />
                Files:
                <CustomerFileCreate class="float-end" :equipment="equipment" />
            </div>
            <Overlay :loading="loading.files">
                <div v-if="!files.length">
                    <h6 class="text-center">No Files</h6>
                </div>
                <Table :columns="tableColumns" :rows="files" no-inertia-link>
                    <template #action="{ rowData }">
                        <span
                            class="badge bg-info rounded-pill pointer"
                            title="Show File Details"
                            v-tooltip
                            @click="showFileDetails(rowData)"
                        >
                            <fa-icon icon="circle-info" />
                        </span>
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
import Overlay from "../_Base/Loaders/Overlay.vue";
import Table from "../_Base/Table.vue";
import CustomerFileCreate from "@/Components/Customer/CustomerFileCreate.vue";
import CustomerFileDetails from "@/Components/Customer/CustomerFileDetails.vue";
import { nextTick, reactive, ref } from "vue";
import { loading, toggleLoading, files } from "@/State/CustomerState";

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
