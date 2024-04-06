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
                            @click="updateSort(col.sortField || col.field)"
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
                <tr v-if="!paginatedData.length">
                    <td :colspan="columnCount">
                        <slot name="no-results">
                            <div class="text-center">
                                {{ noResultsText || "No Results" }}
                            </div>
                        </slot>
                    </td>
                </tr>
                <tr
                    v-for="(row, index) in paginatedData"
                    :key="index"
                    :class="{ pointer: rowClickable, 'row-link': row.href }"
                >
                    <template v-for="col in columns">
                        <td @click="$emit('on-row-click', [$event, row])">
                            <a
                                v-if="row.href && noInertiaLink"
                                :href="row.href"
                                class="block-link"
                            >
                                <span v-if="col.isBoolean">
                                    <fa-icon icon="check" />
                                </span>
                                <span v-else>
                                    {{ row[col.field] }}
                                </span>
                            </a>
                            <Link
                                v-else-if="row.href"
                                :href="row.href"
                                class="block-link"
                            >
                                <span v-if="col.isBoolean">
                                    <fa-icon icon="check" />
                                </span>
                                <span v-else>
                                    {{ row[col.field] }}
                                </span>
                            </Link>
                            <span v-else>
                                <span v-if="col.isBoolean">
                                    <fa-icon
                                        :icon="
                                            row[col.field]
                                                ? 'circle-check'
                                                : 'circle-xmark'
                                        "
                                        :class="
                                            row[col.field]
                                                ? 'text-success'
                                                : 'text-danger'
                                        "
                                    />
                                </span>
                                <span v-else>
                                    {{ row[col.field] }}
                                </span>
                            </span>
                        </td>
                    </template>
                    <td v-if="slots.action">
                        <slot name="action" :row-data="row" />
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <slot name="footer">
                    <tr v-if="paginate">
                        <td :colspan="columnCount">
                            <span class="float-md-start w-auto">
                                <select v-model="perPage" class="form-select">
                                    <option
                                        v-for="num in perPageArray"
                                        :value="num"
                                    >
                                        {{ num }}
                                    </option>
                                </select>
                            </span>
                            <span
                                class="float-md-end w-auto d-block d-md-inline text-center"
                            >
                                Showing {{ showingStart }}-{{ showingEnd }} of
                                {{ totalRecords }}
                            </span>
                            <Pagination
                                :currentPage="currentPage"
                                :totalPages="totalPages"
                                @prev-page="currentPage--"
                                @next-page="currentPage++"
                                @go-to-page="goToPage"
                            />
                        </td>
                    </tr>
                </slot>
            </tfoot>
        </table>
    </div>
</template>

<script setup lang="ts">
import Pagination from "./Pagination.vue";
import { ref, computed, watch, useSlots } from "vue";
import { sortDataObject } from "@/Modules/SortDataObject.module";

interface column {
    label: string;
    field: string;
    sort?: boolean;
    sortField?: string;
    isBoolean?: boolean;
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
    paginate?: boolean;
    perPageArray?: number[];
    perPageDefault?: number;
    noResultsText?: string;
    noInertiaLink?: boolean;
}>();

const columnCount = computed(
    () => props.columns.length + (slots.action ? 1 : 0)
);

/*******************************************************************************
 * The modified list that has been filtered and sorted
 *******************************************************************************/
const sortedData = computed(() =>
    sortDataObject(filteredData.value, sortOrder.value, sortBy.value)
);

/*******************************************************************************
 * Sorting Properties
 *******************************************************************************/
const sortBy = ref<string>(props.initialSort || "");
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
        if (
            (sortBy.value === col.field || sortBy.value === col.sortField) &&
            sortOrder.value === "asc"
        ) {
            icons[col.field] = "sort-down";
        } else if (
            (sortBy.value === col.field || sortBy.value === col.sortField) &&
            sortOrder.value === "desc"
        ) {
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

/*******************************************************************************
 * Pagination Properties
 *******************************************************************************/
const perPageArray = props.perPageArray || [10, 25, 50, 100];

const totalRecords = computed(() => sortedData.value.length);
const showingStart = computed(
    () => (currentPage.value - 1) * perPage.value + 1
);
const showingEnd = computed(
    () => showingStart.value + paginatedData.value.length - 1
);

const perPage = ref(props.perPageDefault || 10);
const currentPage = ref(1);
const totalPages = computed(() =>
    Math.ceil(totalRecords.value / perPage.value)
);

// If the data is resorted, and there are less records than current page, jump to page 1
watch(totalPages, (newTotalPages) => {
    if (newTotalPages < currentPage.value) {
        currentPage.value = 1;
    }
});

// Chunk of results to show on current page
const paginatedData = computed(() => {
    // If Pagination is turned off, we just pass the filtered array
    if (!props.paginate) {
        return sortedData.value;
    }

    // FIXME - cannot sort data when paginating
    return sortedData.value.slice(
        (currentPage.value - 1) * perPage.value,
        currentPage.value * perPage.value
    );
});

// Navigate to a specific page
const goToPage = (newPage: number) => {
    currentPage.value = newPage;
};
</script>

<style scoped lang="scss">
tr.row-link {
    padding: 0;
    margin: 0;
    a.block-link {
        display: block;
        padding: 0.5rem;
        margin: 0;
        height: 100%;
    }
}
</style>
