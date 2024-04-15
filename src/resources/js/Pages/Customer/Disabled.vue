<template>
    <div class="row justify-content-center">
        <Head title="Disabled Customers" />
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Disabled Customers</div>
                    <Table
                        :columns="cols"
                        :rows="disabledList"
                        no-results-text="No Deleted Customers Found"
                    >
                        <template #action="{ rowData }">
                            <RestoreBadge @click="restoreCustomer(rowData)" />
                            <DeleteBadge @click="destroyCustomer(rowData)" />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/_Base/Table.vue";
import RestoreBadge from "@/Components/_Base/Badges/RestoreBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    disabledList: customer[];
}>();

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
];

const restoreCustomer = (customer: customer) => {
    verifyModal("This customer will be restored and usable again").then(
        (res) => {
            if (res) {
                console.log("restore");
                router.get(
                    route("customers.disabled.restore", customer.cust_id)
                );
            }
        }
    );
};

const destroyCustomer = (customer: customer) => {
    verifyModal(
        "This customer and all associated data will be removed.  This cannot be undone."
    ).then((res) => {
        if (res) {
            console.log("force delete");
            router.delete(
                route("customers.disabled.force-delete", customer.cust_id)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
