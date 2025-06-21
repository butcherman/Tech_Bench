<script setup lang="ts" generic="T">
import { inject } from "vue";
import type { Table } from "@tanstack/vue-table";

defineProps<{
    noResultsText?: string;
}>();

const table = inject<Table<T>>("table");
</script>

<template>
    <tr :key="0">
        <td :colspan="table?.getAllColumns().length">
            <slot name="no-results">
                <h3 class="text-center text-muted">
                    {{ noResultsText ?? "No Results" }}
                </h3>
            </slot>
        </td>
    </tr>
</template>
