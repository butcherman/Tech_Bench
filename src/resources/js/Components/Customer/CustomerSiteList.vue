<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <Link
                    v-if="permissions.details.create"
                    :href="$route('customers.sites.create', customer.slug)"
                    class="float-end"
                >
                    <AddButton text="Add Site" small pill />
                </Link>
                Sites:
            </div>
            <Table
                :columns="columns"
                :rows="siteList"
                :paginate="siteList.length > 10"
                responsive
                rowClickable
            >
                <template #action="{ rowData }">
                    <span
                        v-if="rowData.is_primary"
                        class="text-primary"
                        title="Primary Site"
                        v-tooltip
                    >
                        <fa-icon icon="paper-plane" />
                    </span>
                </template>
            </Table>
        </div>
    </div>
</template>

<script setup lang="ts">
import Table from "../_Base/Table.vue";
import AddButton from "../_Base/Buttons/AddButton.vue";
import { siteList, permissions, customer } from "@/State/CustomerState";

const columns = [
    {
        label: "Site Name",
        field: "site_name",
    },
    {
        label: "City",
        field: "city",
    },
];
</script>
