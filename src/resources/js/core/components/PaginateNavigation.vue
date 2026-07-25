<script setup lang="ts">
import { computed } from "vue";

const emit = defineEmits<{
    goToPage: [number];
}>();

const props = defineProps<{
    currentPage: number;
    totalPages: number;
}>();

/*
|-------------------------------------------------------------------------------
| Navigation go-to-page links for navigating to a specific page.
| Only five pages will show with the active page in the middle if more
| than five available.
|-------------------------------------------------------------------------------
*/
const paginationArray = computed<number[]>(() => {
    let pageArr: number[] = [];
    let start: number = props.totalPages > 5 ? props.currentPage - 2 : 1;

    //  If start was going to be a negative number, we change it to 1
    if (start <= 0) {
        start = 1;
    }

    let end = props.totalPages > 5 ? start + 4 : props.totalPages;
    //  If end was going to be a higher number than the last page, we modify it
    if (end > props.totalPages) {
        end = props.totalPages;
        //  Try to still get five links in the array
        if (props.totalPages > 5) {
            start = props.totalPages - 4;
        }
    }

    for (let i = start; i <= end; i++) {
        pageArr.push(i);
    }

    return pageArr;
});

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
    <div>
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
