<script setup lang="ts">
import { computed } from "vue";

defineEmits(["prevPage", "nextPage", "goToPage"]);

const props = defineProps<{
    currentPage: number;
    totalPages: number;
}>();

/**
 * Build the pagination links for the bottom of the table
 * We want a total of five pages showing, the active page should be in the
 * middle unless it is toward the front of end of the line
 */
const paginationArray = computed(() => {
    let pageArr = [];
    let start = props.totalPages > 5 ? props.currentPage - 2 : 1;

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
</script>

<template>
    <ul class="pagination pagination-sm justify-content-center">
        <li
            class="page-item"
            :class="{ disabled: currentPage === 1 }"
            @click="$emit('goToPage', 1)"
        >
            <span class="page-link pointer" title="First Page" v-tooltip>
                <fa-icon icon="angles-left" />
            </span>
        </li>
        <li
            class="page-item"
            :class="{ disabled: currentPage === 1 }"
            @click="$emit('prevPage')"
        >
            <span class="page-link pointer" title="Previous Page" v-tooltip>
                <fa-icon icon="angle-left" />
            </span>
        </li>
        <li
            v-for="index in paginationArray"
            :key="index"
            class="page-item"
            @click="$emit('goToPage', index)"
        >
            <span
                class="page-link pointer"
                :class="{ active: currentPage === index }"
            >
                {{ index }}
            </span>
        </li>
        <li
            class="page-item"
            :class="{ disabled: currentPage === totalPages }"
            @click="$emit('nextPage')"
        >
            <span class="page-link pointer" title="Next Page" v-tooltip>
                <fa-icon icon="fa-angle-right" />
            </span>
        </li>
        <li
            class="page-item"
            :class="{ disabled: currentPage === totalPages }"
            @click="$emit('goToPage', totalPages)"
        >
            <span class="page-link pointer" title="Last Page" v-tooltip>
                <fa-icon icon="fa-angles-right" />
            </span>
        </li>
    </ul>
</template>
