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
                    <th v-if="slots.action" />
                </tr>
                <tr v-if="canFilter">
                    <td v-for="col in columns" :key="col.field">
                        <input
                            v-model="filterValues[col.field]"
                            type="text"
                            class="form-control"
                            :disabled="!col.filterOptions?.enabled"
                            :placeholder="col.filterOptions?.placeholder"
                        />
                    </td>
                    <td v-if="slots.action" />
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(row, index) in sortedData"
                    :key="index"
                    :class="{ pointer: rowClickable }"
                >
                    <template v-for="col in columns">
                        <td @click="$emit('on-row-click', row)">
                            {{ row[col.field] }}
                        </td>
                    </template>
                    <td v-if="slots.action">
                        <slot name="action" :row-data="row" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, useSlots } from "vue";
import { sortDataObject } from "@/Modules/SortDataObject.module";

// TODO - Add Pagination to Table

interface column {
    label: string;
    field: string;
    sort?: boolean;
    filterOptions?: {
        enabled: boolean;
        placeholder?: string;
    };
}

defineEmits(["on-row-click"]);
const slots = useSlots();
const props = defineProps<{
    columns: column[];
    rows: any[];
    responsive?: boolean;
    initialSort?: string;
    rowClickable?: boolean;
}>();

/*******************************************************************************
 * The modified list that has been filtered and sorted
 *******************************************************************************/
const sortedData = computed(() =>
    sortDataObject(filteredData.value, sortOrder.value, sortBy.value)
);

/*******************************************************************************
 * Sorting Properties
 *******************************************************************************/
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

// Sort up/down icons for each column
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

/*******************************************************************************
 * Filtering Properties
 *******************************************************************************/
const filterValues = ref<{ [key: string]: string }>({});

// If any of the Filter Options values are set to true, we will show the filter inputs
const canFilter = computed(() => {
    let filterAllowed = false;

    props.columns.forEach((col) => {
        if (col.filterOptions?.enabled) {
            filterAllowed = true;
        }
    });

    return filterAllowed;
});

// Filter rows based on search input for each column
const filteredData = computed(() => {
    const filteredData: any[] = [];
    const filterKeys = Object.keys(filterValues.value);

    props.rows.forEach((row) => {
        let hasFilter = true;
        filterKeys.forEach((key) => {
            if (filterValues.value[key].length) {
                if (
                    !row[key]
                        .toLowerCase()
                        .includes(filterValues.value[key].toLowerCase())
                ) {
                    hasFilter = false;
                }
            }
        });

        if (hasFilter) {
            filteredData.push(row);
        }
    });

    return filteredData;
});
</script>
