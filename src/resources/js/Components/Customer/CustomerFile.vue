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
                    <template #action>action</template>
                </Table>
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive } from "vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import Overlay from "../_Base/Loaders/Overlay.vue";
import Table from "../_Base/Table.vue";
import CustomerFileCreate from "@/Components/Customer/CustomerFileCreate.vue";
import { loading, toggleLoading, files } from "@/State/CustomerState";

defineProps<{
    equipment?: customerEquipment;
}>();

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
