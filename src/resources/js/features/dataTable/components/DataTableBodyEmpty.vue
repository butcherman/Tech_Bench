<script setup lang="ts" generic="TData">
import type { Table } from "@tanstack/vue-table";

defineProps<{
    table: Table<TData>;
    noResultsText?: string;
}>();
</script>

<template>
    <tr :key="0">
        <td :colspan="table.getAllColumns().length">
            <slot name="no-results">
                <h3 class="text-center text-muted">
                    {{ noResultsText ?? "No Results" }}
                </h3>
            </slot>
        </td>
    </tr>
</template>
