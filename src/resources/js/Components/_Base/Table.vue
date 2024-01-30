<template>
    <div :class="{ 'table-responsive': responsive }">
        <table class="table">
            <thead>
                <tr>
                    <th v-for="col in columns" :key="col.field">
                        {{ col.label }}
                        <span
                            v-if="col.sort"
                            class="float-end pointer"
                            @click="updateSort(col.field)"
                        >
                            <fa-icon :icon="sortIcons[col.field]" />
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(row, index) in sortedData"
                    :key="index"
                    :class="{ pointer: rowClickable }"
                    @click="$emit('on-row-click', row)"
                >
                    <template v-for="col in columns">
                        <td>
                            {{ row[col.field] }}
                        </td>
                    </template>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { sortDataObject } from "@/Modules/SortDataObject.module";

interface column {
    label: string;
    field: string;
    sort?: boolean;
}

defineEmits(["on-row-click"]);
const props = defineProps<{
    columns: column[];
    rows: any[];
    responsive?: boolean;
    initialSort?: string;
    rowClickable?: boolean;
}>();

const sortedData = computed(() =>
    sortDataObject(props.rows, sortOrder.value, sortBy.value)
);

/**
 * Sorting Properties
 */
const sortBy = ref<string>(props.initialSort || props.columns[0].field);
const sortOrder = ref<"asc" | "desc">("asc");

const updateSort = (field: string) => {
    let newOrder: "asc" | "desc" = "asc";

    if (sortBy.value === field) {
        sortOrder.value === "asc" ? (newOrder = "desc") : (newOrder = "asc");
        sortOrder.value = newOrder;
    }

    sortBy.value = field;
    sortOrder.value = newOrder;
};

const sortIcons = computed<{ [key: string]: string }>(() => {
    let icons: { [key: string]: string } = {};

    props.columns.forEach((col: column) => {
        if (sortBy.value === col.field && sortOrder.value === "asc") {
            icons[col.field] = "sort-down";
        } else if (sortBy.value === col.field && sortOrder.value === "desc") {
            icons[col.field] = "sort-up";
        } else {
            icons[col.field] = "sort";
        }
    });

    return icons;
});
</script>
