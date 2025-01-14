<template>
    <div class="relative">
        <input
            v-model="localValue"
            type="text"
            class="border p-1 ps-2 rounded-md w-full"
            :placeholder="placeholder"
            @keyup="updateFilter"
        />
        <fa-icon
            icon="filter"
            class="absolute top-1/2 transform -translate-y-1/2 right-3 text-muted"
        />
    </div>
</template>

<script setup lang="ts" generic="T">
import { ref } from "vue";
import type { Column } from "@tanstack/vue-table";

const props = defineProps<{
    column: Column<T>;
}>();

const localValue = ref<string>("");
const placeholder = ref<string>(
    props.column.columnDef.meta?.filterPlaceholder ?? ""
);

const updateFilter = (): void => {
    props.column.setFilterValue(localValue.value);
};
</script>
