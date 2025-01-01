<template>
    <Card title="Disabled Customers" class="tb-card">
        <v-data-table :headers="cols" :items="disabledList">
            <template #item.actions="{ item }">
                <RestoreBadge @click="restoreCustomer(item)" />
                <DeleteBadge @click="destroyCustomer(item)" />
            </template>
        </v-data-table>
    </Card>
</template>

<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import VerifyModal from "@/Modules/VerifyModal";
import { router } from "@inertiajs/vue3";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import RestoreBadge from "@/Components/_Base/Badges/RestoreBadge.vue";

const props = defineProps<{
    disabledList: customer[];
}>();

/**
 * Restore a Soft Deleted Customer
 */
const restoreCustomer = (customer: customer) => {
    VerifyModal("This customer will be restored and usable again").then(
        (res) => {
            if (res) {
                router.get(
                    route("customers.disabled.restore", customer.cust_id)
                );
            }
        }
    );
};

/**
 * Completely destroy a customer and all related data.
 */
const destroyCustomer = (customer: customer) => {
    VerifyModal(
        "This customer and all associated data will be removed.  This cannot be undone."
    ).then((res) => {
        if (res) {
            router.delete(
                route("customers.disabled.force-delete", customer.cust_id),
                {
                    onFinish: () => {
                        let index = props.disabledList.indexOf(customer);
                        props.disabledList.splice(index, 1);
                    },
                }
            );
        }
    });
};

const cols = [
    {
        title: "Customer Name",
        value: "name",
    },
    {
        title: "# of Sites",
        value: "site_count",
    },
    {
        title: "Disabled Date",
        value: "deleted_at",
    },
    {
        title: "Disabled Reason",
        value: "deleted_reason",
    },
    {
        value: "actions",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
