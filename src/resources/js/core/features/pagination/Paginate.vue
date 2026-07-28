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
    <div class="flex flex-row w-full">
        <PaginatePerPageSelector
            v-model:per-page="perPageInternal"
            :per-page-array="perPageArray"
        />
        <PaginationNavigation
            class="grow"
            :current-page="currentPage"
            :total-pages="totalPages"
            :pagination-array="paginationArray"
            @go-to-page="$emit('goToPage', $event)"
            @next-page="$emit('nextPage', $event)"
            @prev-page="$emit('prevPage', $event)"
        />
        <PaginateNumberResults
            :record-start="recordStart"
            :record-end="recordEnd"
            :total-records="totalRecords"
        />
    </div>
</template>
