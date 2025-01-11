<template>
    <div class="w-full h-full">
        <AgGridVue
            :row-data="rows"
            :column-defs="columns"
            :default-col-def="defaultColDef"
            :row-class-rules="rowClassRules"
            :pagination="paginate"
            class="h-full w-full min-h-40"
            @row-clicked="$emit('row-clicked')"
        ></AgGridVue>
    </div>
</template>

<script setup lang="ts">
import { AgGridVue } from "ag-grid-vue3";
import { AllCommunityModule, ModuleRegistry } from "ag-grid-community";
import { ref } from "vue";

/**
 * TODO:
 *  Sort by raw date or other field
 *  Row Spanning
 */

interface dataColumn {
    headerName: string;
    field: string;
    flex?: number;
    filter?: boolean;
    floatingFilter?: boolean;
    sortable?: boolean;
}

const emit = defineEmits<{
    "row-clicked": [];
}>();
const props = defineProps<{
    columns: dataColumn[];
    rows: any[];
    rowClassRules?: { [key: string]: string };
    paginate?: boolean;
}>();

ModuleRegistry.registerModules([AllCommunityModule]);

const defaultColDef = {
    flex: 1,
    sortable: false,
};
</script>
