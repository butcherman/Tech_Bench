<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import NewFileModal from "./NewFileModal.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import { Deferred } from "@inertiajs/vue3";
import { fileList } from "@/Composables/Customer/CustomerData.module";
import { ref, useTemplateRef } from "vue";
import {
    clearNotification,
    notificationStatus,
} from "@/Composables/Customer/CustomerBroadcasting.module";
import type { tableColumnProp } from "@/Components/_Base/DataTable/DataTable.vue";

const addModal = useTemplateRef("new-file-modal");

/*
|-------------------------------------------------------------------------------
| Loading State
|-------------------------------------------------------------------------------
*/
const isLoading = ref<boolean>(false);

/**
 * Start the loading process when the refresh button clicked.
 */
const onRefreshStart = (): void => {
    isLoading.value = true;
};

/**
 * End the loading process and clear the alert icon.
 */
const onRefreshEnd = (): void => {
    isLoading.value = false;
    clearNotification("files");
};

const tableColumns: tableColumnProp[] = [
    {
        label: "Name",
        field: "name",
        sort: true,
        filterable: true,
    },
    {
        label: "Type",
        field: "file_type",
        sort: true,
        filterable: true,
        filterSelect: true,
    },
    {
        label: "Uploaded On",
        field: "created_at",
        sort: true,
    },
    {
        field: "actions",
    },
];
</script>

<template>
    <Card>
        <template #title>
            <AlertButton v-if="notificationStatus.files" />
            <RefreshButton
                flat
                :only="['fileList']"
                @loading-start="onRefreshStart"
                @loading-complete="onRefreshEnd"
            />
            Files
            <AddButton
                class="float-end"
                size="small"
                text="Add File"
                pill
                @click="addModal?.show()"
            />
        </template>
        <Deferred data="fileList">
            <template #fallback>
                <div class="flex justify-center">
                    <AtomLoader />
                </div>
            </template>
            <Overlay :loading="isLoading">
                <!-- <DataTable
                    :columns="tableColumns"
                    :rows="fileList"
                    no-results-text="No Files"
                    paginate
                    allow-row-click
                    striped
                >
                    <template #row.actions="{ rowData }">
                        <div class="size-fit float-end">
                            <BaseBadge icon="circle-info" class="me-1" />
                            <EditBadge class="me-1" />
                            <DeleteBadge class="me-1" />
                        </div>
                    </template>
                </DataTable> -->
                {{ fileList }}
            </Overlay>
        </Deferred>
        <NewFileModal
            ref="new-file-modal"
            @refresh-start="onRefreshStart"
            @refresh-end="onRefreshEnd"
        />
    </Card>
</template>
