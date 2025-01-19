<template>
    <Card class="tb-card" title="Disabled Customers">
        <DataTable
            :columns="cols"
            :rows="disabledList"
            striped
            sync-loading-state
        >
            <template #row.actions="{ rowData }">
                <BaseBadge
                    class="mx-1 pointer"
                    icon="trash-restore"
                    v-tooltip="'Restore Customer'"
                    @click="restoreCustomer(rowData)"
                />
                <DeleteBadge
                    class="mx-1"
                    confirm-msg="This customer and all associated data will be removed"
                    confirm
                    v-tooltip="'Delete Customer'"
                    @accepted="destroyCustomer(rowData)"
                />
            </template>
        </DataTable>
    </Card>
</template>

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

const destroyCustomer = (cust: customer) => {
    router.delete(route("customers.disabled.force-delete", cust.cust_id));
};

const restoreCustomer = (customer: customer) => {
    verifyModal("This customer will be restored and usable again").then(
        (res) => {
            if (res) {
                router.get(
                    route("customers.disabled.restore", customer.cust_id)
                );
            }
        }
    );
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
