<template>
    <div>
        <Table
            :columns="cols"
            :rows="searchResults"
            initial-sort="sticky"
            responsive
        >
            <template #column="{ columnName, rowData }">
                <span v-if="columnName === 'sticky'">
                    <span v-if="rowData.sticky" class="text-danger">
                        <fa-icon icon="thumbtack" class="rotate-45" />
                    </span>
                </span>
            </template>
        </Table>
    </div>
</template>

<script setup lang="ts">
import Table from "../_Base/Table.vue";
import { searchResults } from "@/Modules/TipSearch.module";

const cols = [
    {
        label: "",
        field: "sticky",
    },
    {
        label: "Title",
        field: "subject",
    },
    {
        label: "Date",
        field: "updated_at",
    },
];
</script>

<style scoped lang="scss">
.rotate-45 {
    transform: rotate(45deg);
}
</style>
