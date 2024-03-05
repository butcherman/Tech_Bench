<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <span class="float-end">
                    <slot name="add-button"> </slot>
                </span>
                {{ titleText || "Sites" }}:
            </div>
            <Table
                :columns="columns"
                :rows="siteList"
                :paginate="siteList.length > 10"
                responsive
                rowClickable
                :no-results-text="noResultsText"
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
import { computed } from "vue";
import { siteList } from "@/State/CustomerState";

const props = defineProps<{
    titleText?: string;
    emptyText?: string;
}>();

const noResultsText = computed(
    () =>
        props.emptyText ||
        "No Sites attached to this Customer.  Please add one."
);

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
