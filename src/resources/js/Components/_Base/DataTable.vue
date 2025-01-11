<template>
    <div class="w-full h-full">
        <AgGridVue
            :row-data="rows"
            :column-defs="columns"
            :default-col-def="defaultColDef"
            class="h-full w-full min-h-40"
        ></AgGridVue>
    </div>
</template>

<script setup lang="ts">
import { AgGridVue } from "ag-grid-vue3";
import { AllCommunityModule, ModuleRegistry } from "ag-grid-community";
import { ref } from "vue";

interface dataColumn {
    headerName: string;
    field: string;
    flex?: number;
    filter?: boolean;
    floatingFilter?: boolean;
    sortable?: boolean;
}

const props = defineProps<{
    columns: dataColumn[];
    rows: any[];
}>();

ModuleRegistry.registerModules([AllCommunityModule]);

const defaultColDef = {
    flex: 1,
    sortable: false,
};
</script>
