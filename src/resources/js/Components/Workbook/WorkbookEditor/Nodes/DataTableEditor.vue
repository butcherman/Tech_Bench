<script setup lang="ts">
import DataTable from "../../WorkbookNodes/DataTable.vue";
import NodeOptions from "../NodeOptions.vue";

const props = defineProps<{
    allowAddRow: boolean;
    allowDeleteRow: boolean;
    allowExport: boolean;
    allowImport: boolean;
    columns: workbookTableColumn[];
    defaultRows: number;
    hideBorders: boolean;
    index: string;
    numberRows: boolean;
}>();
</script>

<template>
    <div class="relative group/table">
        <NodeOptions
            class="hidden group-hover/table:flex"
            :can-edit="true"
            :node-index="index"
        />
        <DataTable v-bind="props" />
    </div>
</template>
