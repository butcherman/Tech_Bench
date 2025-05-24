<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import verifyModal from "@/Modules/verifyModal";
import { router, Deferred } from "@inertiajs/vue3";

defineProps<{
    linkList?: fileLink[];
}>();

/**
 * Determine if a red background should be displayed on the table row.
 */
const isExpiredBg = (row: fileLink): string => {
    return row.is_expired ? "bg-red-300" : "";
};

/**
 * Disable link by setting expires date to yesterday.
 */
const disableLink = (row: fileLink): void => {
    verifyModal(
        "Link and its files will no longer be accessible publicly"
    ).then((res) => {
        if (res) {
            router.get(route("links.expire", row.link_id));
        }
    });
};

/**
 * Delete a File Link and all associated files
 */
const deleteLink = (row: fileLink): void => {
    verifyModal("Link and all associated files will be deleted").then((res) => {
        if (res) {
            router.delete(route("links.destroy", row.link_id));
        }
    });
};

const cols = [
    {
        label: "Link Name",
        field: "link_name",
        filterable: true,
        sort: true,
    },
    {
        label: "Expires",
        field: "expire",
        filterable: true,
        sort: true,
    },
    {
        field: "actions",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="File Upload Links">
            <template #append-title>
                <AddButton
                    text="New File Link"
                    size="small"
                    :href="$route('links.create')"
                    pill
                />
            </template>
            <Deferred data="link-list">
                <template #fallback>
                    <div class="flex justify-center">
                        <AtomLoader />
                    </div>
                </template>
                <DataTable
                    :columns="cols"
                    :rows="linkList"
                    :row-bg-fn="isExpiredBg"
                    :row-click-link="(row) => row.href"
                >
                    <template #row.actions="{ rowData }">
                        <div class="flex justify-end">
                            <BaseBadge
                                v-if="!rowData.is_expired"
                                class="me-1"
                                icon="link-slash"
                                variant="warning"
                                v-tooltip="'Disable Link'"
                                @click.stop="disableLink(rowData)"
                            />
                            <DeleteBadge
                                v-tooltip="'Delete Link'"
                                @click.stop="deleteLink(rowData)"
                            />
                        </div>
                    </template>
                </DataTable>
            </Deferred>
        </Card>
    </div>
</template>
