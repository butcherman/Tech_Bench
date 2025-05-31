<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import verifyModal from "@/Modules/verifyModal";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { router } from "@inertiajs/vue3";

interface deletedItems {
    equipment: customerEquipment[];
    contacts: customerContact[];
    notes: customerNote[];
    files: customerFile[];
    sites: customerSite[];
}

defineProps<{
    deletedItems: deletedItems;
}>();

/**
 * Remove the Soft Delete flag from a previously disabled item.
 */
const restoreItem = (category: string, itemId: number): void => {
    verifyModal("This item will be restored").then((res) => {
        if (res) {
            router.get(
                route(`customers.deleted-items.restore.${category}`, [
                    customer.value.slug,
                    itemId,
                ])
            );
        }
    });
};

/**
 * Permanently Delete a soft deleted item.
 */
const destroyItem = (category: string, itemId: number): void => {
    verifyModal("This Action Cannot Be Undone").then((res) => {
        if (res) {
            router.delete(
                route(`customers.deleted-items.force-delete.${category}`, [
                    customer.value.slug,
                    itemId,
                ]),
                {
                    preserveScroll: true,
                    preserveState: true,
                }
            );
        }
    });
};

/**
 * Restore a soft deleted site
 */
const restoreSite = (siteId: number): void => {
    verifyModal("Restore this site?").then((res) => {
        if (res) {
            //
            router.get(
                route("customers.sites.restore", [customer.value.slug, siteId])
            );
        }
    });
};

/**
 * Delete a site and all attached data
 */
const destroySite = (siteId: number): void => {
    verifyModal("Delete this site?", "WARNING: POSSIBLE DATA LOSS").then(
        (res) => {
            if (res) {
                //
                router.delete(
                    route("customers.sites.forceDelete", [
                        customer.value.slug,
                        siteId,
                    ])
                );
            }
        }
    );
};

const cols = [
    {
        label: "Site Name",
        field: "site_name",
    },
    {
        label: "Disabled Date",
        field: "deleted_at",
    },
    {
        label: "Disabled Reason",
        field: "deleted_reason",
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
    <div>
        <div class="flex gap-2 pb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
        </div>
        <div v-if="deletedItems.sites.length" class="tb-gap-y">
            <Card title="Disabled Sites">
                <DataTable :columns="cols" :rows="deletedItems.sites">
                    <template #row.actions="{ rowData }">
                        <BaseBadge
                            icon="trash-restore"
                            v-tooltip.left="'Restore Site'"
                            @click="restoreSite(rowData.cust_site_id)"
                        />
                        <DeleteBadge
                            class="ms-1"
                            v-tooltip.left="'Delete Site'"
                            @click="destroySite(rowData.cust_site_id)"
                        />
                    </template>
                </DataTable>
            </Card>
        </div>
        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
            <Card title="Deleted Equipment">
                <ResourceList
                    :list="deletedItems.equipment"
                    label-field="equip_name"
                    empty-text="No Deleted Equipment"
                >
                    <template #actions="{ item }">
                        <BaseBadge
                            icon="trash-restore"
                            class="me-1"
                            v-tooltip.left="'Restore Equipment'"
                            @click="
                                restoreItem('equipment', item.cust_equip_id)
                            "
                        />
                        <DeleteBadge
                            v-tooltip.left="'Destroy Equipment'"
                            @click="
                                destroyItem('equipment', item.cust_equip_id)
                            "
                        />
                    </template>
                </ResourceList>
            </Card>
            <Card title="Deleted Equipment">
                <ResourceList
                    :list="deletedItems.contacts"
                    label-field="name"
                    empty-text="No Deleted Contacts"
                >
                    <template #actions="{ item }">
                        <BaseBadge
                            icon="trash-restore"
                            class="me-1"
                            v-tooltip.left="'Restore Contact'"
                            @click="restoreItem('contacts', item.cont_id)"
                        />
                        <DeleteBadge
                            v-tooltip.left="'Destroy Contact'"
                            @click="destroyItem('contacts', item.cont_id)"
                        />
                    </template>
                </ResourceList>
            </Card>
            <Card title="Deleted Notes">
                <ResourceList
                    :list="deletedItems.notes"
                    label-field="subject"
                    empty-text="No Deleted Notes"
                >
                    <template #actions="{ item }">
                        <BaseBadge
                            icon="trash-restore"
                            class="me-1"
                            v-tooltip.left="'Restore Note'"
                            @click="restoreItem('notes', item.note_id)"
                        />
                        <DeleteBadge
                            v-tooltip.left="'Destroy Note'"
                            @click="destroyItem('notes', item.note_id)"
                        />
                    </template>
                </ResourceList>
            </Card>
            <Card title="Deleted Files">
                <ResourceList
                    :list="deletedItems.files"
                    label-field="name"
                    empty-text="No Deleted Files"
                >
                    <template #actions="{ item }">
                        <BaseBadge
                            icon="trash-restore"
                            class="me-1"
                            v-tooltip.left="'Restore File'"
                            @click="restoreItem('files', item.cust_file_id)"
                        />
                        <DeleteBadge
                            v-tooltip.left="'Destroy File'"
                            @click="destroyItem('files', item.cust_file_id)"
                        />
                    </template>
                </ResourceList>
            </Card>
        </div>
    </div>
</template>
