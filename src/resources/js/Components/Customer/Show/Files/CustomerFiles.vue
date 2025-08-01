<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import EditFileModal from "./EditFileModal.vue";
import FileDetailsModal from "./FileDetailsModal.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import NewFileModal from "./NewFileModal.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import verifyModal from "@/Modules/verifyModal";
import { getIconFromFilename } from "@/Composables/fileIcons.module";
import { Deferred, router } from "@inertiajs/vue3";
import { customer, fileList } from "@/Composables/Customer/CustomerData.module";
import { ref, useTemplateRef } from "vue";
import {
    clearNotification,
    notificationStatus,
} from "@/Composables/Customer/CustomerBroadcasting.module";
import type { tableColumnProp } from "@/Components/_Base/DataTable/DataTable.vue";

defineProps<{
    equipment?: customerEquipment;
}>();

const addModal = useTemplateRef("new-file-modal");
const detailsModal = useTemplateRef("file-details-modal");
const editModal = useTemplateRef("edit-file-modal");

/**
 * When a row is clicked, the file will be downloaded.
 */
const onRowClick = (row: customerFile) => {
    window.open(row.href);
};

/**
 * Confirm to delete a file, then process result.
 */
const onDeleteClick = (row: customerFile): void => {
    verifyModal("Are You Sure you want to delete this file?").then((res) => {
        if (res) {
            isLoading.value = true;

            router.delete(
                route("customers.files.destroy", [
                    customer.value.slug,
                    row.cust_file_id,
                ]),
                {
                    preserveScroll: true,
                    only: ["fileList"],
                    onFinish: () => (isLoading.value = false),
                }
            );
        }
    });
};

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
        label: "File For",
        field: "file_category",
        sort: true,
        filterable: true,
        filterSelect: true,
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
                <DataTable
                    :columns="tableColumns"
                    :rows="fileList"
                    no-results-text="No Files"
                    paginate
                    allow-row-click
                    striped
                    @row-click="onRowClick"
                >
                    <template #row.name="{ rowData }">
                        <span
                            v-html="
                                getIconFromFilename(
                                    rowData.file_upload.file_name
                                )
                            "
                            class="me-1"
                        />
                        {{ rowData.name }}
                    </template>
                    <template #row.actions="{ rowData }">
                        <div class="size-fit float-end">
                            <BaseBadge
                                icon="circle-info"
                                class="me-1"
                                v-tooltip.left="'File Details'"
                                @click.stop="detailsModal?.show(rowData)"
                            />
                            <EditBadge
                                class="me-1"
                                v-tooltip.left="'Edit File Data'"
                                @click.stop="editModal?.show(rowData)"
                            />
                            <DeleteBadge
                                class="me-1"
                                v-tooltip.left="'Delete File'"
                                @click.stop="onDeleteClick(rowData)"
                            />
                        </div>
                    </template>
                </DataTable>
            </Overlay>
        </Deferred>
        <NewFileModal
            ref="new-file-modal"
            :equipment="equipment"
            @refresh-start="onRefreshStart"
            @refresh-end="onRefreshEnd"
        />
        <FileDetailsModal ref="file-details-modal" />
        <EditFileModal ref="edit-file-modal" />
    </Card>
</template>
