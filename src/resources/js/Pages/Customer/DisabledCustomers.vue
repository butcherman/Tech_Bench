<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    disabledList: customer[];
}>();

/**
 * Verify and then restore a disabled customer.
 */
const restoreCustomer = (customer: customer): void => {
    verifyModal("This customer will be restored and accessible again").then(
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
 * Permanently delete a soft deleted customer profile.
 */
const forceDeleteCustomer = (customer: customer): void => {
    verifyModal(
        "This customer and all associated data will be deleted.  This cannot be undone."
    ).then((res) => {
        if (res) {
            router.delete(
                route("customers.disabled.force-delete", customer.cust_id)
            );
        }
    });
};

const cols = [
    {
        label: "Customer Name",
        field: "name",
    },
    {
        label: "# of Sites",
        field: "site_count",
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
    <div class="flex justify-center">
        <Card class="tb-card" title="Disabled Customers">
            <DataTable :columns="cols" :rows="disabledList">
                <template #row.actions="{ rowData }">
                    <BaseBadge
                        icon="trash-restore"
                        v-tooltip="'Restore Customer'"
                        @click="restoreCustomer(rowData)"
                    />
                    <DeleteBadge
                        class="ms-1"
                        v-tooltip="'Delete Customer'"
                        @click="forceDeleteCustomer(rowData)"
                    />
                </template>
            </DataTable>
        </Card>
    </div>
</template>
