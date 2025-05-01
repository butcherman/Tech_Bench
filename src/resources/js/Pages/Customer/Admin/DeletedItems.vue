<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import verifyModal from "@/Modules/verifyModal";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { router } from "@inertiajs/vue3";

interface deletedItems {
    equipment: customerEquipment[];
    contacts: customerContact[];
    // notes: customerNote[];
    // files: customerFile[];
}

defineProps<{
    deletedItems: deletedItems;
}>();

/**
 * Remove the Soft Delete flag from a previously disabled item.
 */
const restoreItem = (category: string, itemId: number): void => {
    console.log("restore item");
    verifyModal("This will restore the equipment and all attached data").then(
        (res) => {
            if (res) {
                router.get(
                    route(`customers.deleted-items.restore.${category}`, [
                        customer.value.slug,
                        itemId,
                    ])
                );
            }
        }
    );
};

/**
 * Permanently Delete a soft deleted item.
 */
const destroyItem = (category: string, itemId: number): void => {
    console.log("destroy item");
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
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 pb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
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
        </div>
    </div>
</template>
