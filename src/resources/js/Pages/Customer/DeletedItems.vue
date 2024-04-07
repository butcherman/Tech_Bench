<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerDetails />
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Deleted Equipment</div>
                        <h6
                            v-if="!deletedItems.equipment.length"
                            class="text-center"
                        >
                            No Deleted Equipment
                        </h6>
                        <ul class="list-group">
                            <li
                                v-for="item in deletedItems.equipment"
                                class="list-group-item"
                            >
                                {{ item.equip_name }}
                                <DeleteBadge
                                    class="float-end"
                                    @click="
                                        destroyDeletedItem(
                                            'equipment',
                                            item.cust_equip_id
                                        )
                                    "
                                />
                                <RestoreBadge
                                    class="float-end"
                                    @click="
                                        restoreDeletedItem(
                                            'equipment',
                                            item.cust_equip_id
                                        )
                                    "
                                />
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Deleted Contacts</div>
                        <h6
                            v-if="!deletedItems.contacts.length"
                            class="text-center"
                        >
                            No Deleted Contacts
                        </h6>
                        <ul class="list-group">
                            <li
                                v-for="item in deletedItems.contacts"
                                class="list-group-item"
                            >
                                {{ item.name }}
                                <DeleteBadge
                                    class="float-end"
                                    @click="
                                        destroyDeletedItem(
                                            'contacts',
                                            item.cont_id
                                        )
                                    "
                                />
                                <RestoreBadge
                                    class="float-end"
                                    @click="
                                        restoreDeletedItem(
                                            'contacts',
                                            item.cont_id
                                        )
                                    "
                                />
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Deleted Notes</div>
                        <h6
                            v-if="!deletedItems.notes.length"
                            class="text-center"
                        >
                            No Deleted Notes
                        </h6>
                        <ul class="list-group">
                            <li
                                v-for="item in deletedItems.notes"
                                class="list-group-item"
                            >
                                {{ item.subject }}
                                <DeleteBadge
                                    class="float-end"
                                    @click="
                                        destroyDeletedItem(
                                            'notes',
                                            item.note_id
                                        )
                                    "
                                />
                                <RestoreBadge
                                    class="float-end"
                                    @click="
                                        restoreDeletedItem(
                                            'notes',
                                            item.note_id
                                        )
                                    "
                                />
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Deleted Files</div>
                        <h6
                            v-if="!deletedItems.files.length"
                            class="text-center"
                        >
                            No Deleted Files
                        </h6>
                        <ul class="list-group">
                            <li
                                v-for="item in deletedItems.files"
                                class="list-group-item"
                            >
                                {{ item.name }}
                                <DeleteBadge
                                    class="float-end"
                                    @click="
                                        destroyDeletedItem(
                                            'files',
                                            item.cust_file_id
                                        )
                                    "
                                />
                                <RestoreBadge
                                    class="float-end"
                                    @click="
                                        restoreDeletedItem(
                                            'files',
                                            item.cust_file_id
                                        )
                                    "
                                />
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import RestoreBadge from "@/Components/_Base/Badges/RestoreBadge.vue";
import { customer } from "@/State/CustomerState";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

interface deletedItems {
    equipment: customerEquipment[];
    contacts: customerContact[];
    notes: customerNote[];
    files: customerFile[];
}

defineProps<{
    deletedItems: deletedItems;
}>();

const restoreDeletedItem = (category: string, itemId: number) => {
    verifyModal("This will restore the equipment and all attached data").then(
        (res) => {
            if (res) {
                router.get(
                    route(`customers.deleted-items.restore.${category}`, [
                        customer.value.cust_id,
                        itemId,
                    ])
                );
            }
        }
    );
};

const destroyDeletedItem = (category: string, itemId: number) => {
    verifyModal("This Action Cannot Be Undone").then((res) => {
        if (res) {
            router.delete(
                route(`customers.deleted-items.force-delete.${category}`, [
                    customer.value.cust_id,
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
