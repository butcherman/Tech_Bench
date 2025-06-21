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

const uniqueValues = Array.from(
    props.column.getFacetedUniqueValues().keys()
).sort();

const updateFilter = (): void => {
    props.column.setFilterValue(localValue.value);
};
</script>

<template>
    <div class="relative">
        <template v-if="column.columnDef.meta?.filterSelect">
            <select
                v-model="localValue"
                class="border p-1 ps-2 rounded-md w-full"
                @change="updateFilter"
            >
                <option :value="null"></option>
                <option v-for="opt in uniqueValues" :value="opt">
                    {{ opt }}
                </option>
            </select>
        </template>
        <template v-else>
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
        </template>
    </div>
</template>
