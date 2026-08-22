<script setup lang="ts">
import { computed } from "vue";

const emit = defineEmits<{
    goToPage: [number];
}>();

const props = defineProps<{
    currentPage: number;
    totalPages: number;
    paginationArray: number[];
}>();

/*
|-------------------------------------------------------------------------------
| Determine if we can navigate to the previous or next page.
|-------------------------------------------------------------------------------
*/
const canGoToNext = computed<boolean>(
    () => props.currentPage < props.totalPages,
);
const canGoToPrev = computed<boolean>(() => props.currentPage > 1);

/*
|-------------------------------------------------------------------------------
| Navigation between pages
|-------------------------------------------------------------------------------
*/
const onNextPage = (): void => {
    if (canGoToNext.value) {
        emit("goToPage", props.currentPage + 1);
    }
};

const onPrevPage = (): void => {
    if (canGoToPrev.value) {
        emit("goToPage", props.currentPage - 1);
    }
};
</script>

<template>
    <div class="flex justify-center">
        <ul class="flex flex-row">
            <li
                class="border rounded-s-lg p-1"
                :class="{
                    pointer: currentPage !== 1,
                    'text-muted': currentPage === 1,
                }"
                @click="$emit('goToPage', 1)"
            >
                <fa-icon icon="angles-left" />
            </li>
            <li
                class="border p-1"
                :class="{
                    pointer: currentPage !== 1,
                    'text-muted': currentPage === 1,
                }"
                @click="onPrevPage"
            >
                <fa-icon icon="angle-left" />
            </li>
            <li
                v-for="page in paginationArray"
                :key="page"
                class="border p-1 pointer"
                :class="{ 'bg-slate-300 font-bold': page === currentPage }"
                @click="$emit('goToPage', page)"
            >
                {{ page }}
            </li>
            <li
                class="border p-1"
                :class="{
                    pointer: currentPage !== totalPages,
                    'text-muted': currentPage === totalPages,
                }"
                @click="onNextPage"
            >
                <fa-icon icon="angle-right" />
            </li>
            <li
                class="border rounded-e-lg p-1"
                :class="{
                    pointer: currentPage !== totalPages,
                    'text-muted': currentPage === totalPages,
                }"
                @click="$emit('goToPage', totalPages)"
            >
                <fa-icon icon="angles-right" />
            </li>
        </ul>
    </div>
</template>
