<script setup lang="ts">
import PaginateNavigation from "./PaginateNavigation.vue";
import { computed } from "vue";

const emit = defineEmits<{
    "update:perPage": [number | string];
    goToPage: [number];
    nextPage: [number];
    prevPage: [number];
}>();

const props = defineProps<{
    paginationArray: number[];
    perPage: number;
    currentPage: number;
    totalRecords: number;
    totalPages: number;
}>();

const perPageInternal = computed<number>({
    get: () => props.perPage,
    set: (value) => emit("update:perPage", Number(value)),
});

/*
|-------------------------------------------------------------------------------
| Starting and ending record for the current page
|-------------------------------------------------------------------------------
*/
const recordStart = computed(() => (props.currentPage - 1) * props.perPage + 1);
const recordEnd = computed(() =>
    Math.min(recordStart.value + props.perPage - 1, props.totalRecords),
);
</script>

<template>
    <div class="flex flex-row justify-between w-full">
        <div>
            <select
                v-model="perPageInternal"
                class="border border-slate-300 rounded-lg p-1"
            >
                <option v-for="option in paginationArray">
                    {{ option }}
                </option>
            </select>
            <span class="hidden lg:inline"> Results Per Page </span>
        </div>
        <div>
            <PaginateNavigation
                :current-page="currentPage"
                :total-pages="totalPages"
                @go-to-page="$emit('goToPage', $event)"
                @next-page="$emit('nextPage', $event)"
                @prev-page="$emit('prevPage', $event)"
            />
        </div>
        <div>
            <span class="hidden lg:inline"> Showing </span>
            {{ recordStart }} - {{ recordEnd }} of
            {{ totalRecords }}
            <span class="hidden lg:inline"> Results </span>
        </div>
    </div>
</template>
