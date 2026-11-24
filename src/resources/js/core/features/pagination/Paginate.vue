<script setup lang="ts">
import PaginateNumberResults from "./components/PaginateNumberResults.vue";
import PaginatePerPageSelector from "./components/PaginatePerPageSelector.vue";
import PaginationNavigation from "./components/PaginationNavigation.vue";
import { computed } from "vue";
import { usePaginationHelper } from "./composables/paginationHelper.js";

const emit = defineEmits<{
    "update:perPage": [number | string];
    goToPage: [number];
    nextPage: [number];
    prevPage: [number];
}>();

const props = defineProps<{
    currentPage: number;
    perPage: number;
    perPageArray: number[];
    totalRecords: number;
}>();

const perPageInternal = computed<number>({
    get: () => props.perPage,
    set: (value) => emit("update:perPage", Number(value)),
});

const { recordStart, recordEnd, totalPages, paginationArray } =
    usePaginationHelper(props);
</script>

<template>
    <div class="grid grid-cols-2 items-center gap-y-2 w-full sm:flex sm:gap-0">
        <PaginatePerPageSelector
            v-model:per-page="perPageInternal"
            :per-page-array="perPageArray"
        />
        <PaginationNavigation
            class="col-span-2 row-start-2 sm:grow sm:order-0"
            :current-page="currentPage"
            :total-pages="totalPages"
            :pagination-array="paginationArray"
            @go-to-page="$emit('goToPage', $event)"
            @next-page="$emit('nextPage', $event)"
            @prev-page="$emit('prevPage', $event)"
        />
        <PaginateNumberResults
            class="col-start-2 row-start-1 sm:order-0"
            :record-start="recordStart"
            :record-end="recordEnd"
            :total-records="totalRecords"
        />
    </div>
</template>
