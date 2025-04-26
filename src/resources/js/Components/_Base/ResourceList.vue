<script setup lang="ts" generic="T">
import Pagination from "./Pagination.vue";
import { computed, ref } from "vue";
import { handleLinkClick } from "../../Composables/links.module";
import { paginated } from "../../Composables/paginateArray.module";

const emit = defineEmits<{
    "row-clicked": [event: MouseEvent, item: T];
}>();

const props = defineProps<{
    list: T[];
    center?: boolean;
    compact?: boolean;
    emptyText?: string;
    hoverRow?: boolean;
    labelField?: keyof T;
    linkFn?: (row: any) => string;
    noBorder?: boolean;
    paginate?: boolean;
    perPage?: number;
}>();

/*
|-------------------------------------------------------------------------------
| Pagination (if enabled)
|-------------------------------------------------------------------------------
*/
const currentPage = ref<number>(0);
const paginationData = computed<T[][]>(() => {
    if (props.paginate) {
        return paginated(props.list, props.perPage ?? 5);
    }

    return [props.list];
});

const currentChunk = computed<T[]>(
    () => paginationData.value[currentPage.value]
);

/**
 * Navigate to a specific page
 */
const goToPage = (page: number): void => {
    currentPage.value = page - 1;
};

/*
|-------------------------------------------------------------------------------
| Style Variables
|-------------------------------------------------------------------------------
*/
const paddingClass = computed<string>(() => (props.compact ? "p-1" : "p-3"));
const hoverClass = computed<string>(() =>
    props.linkFn || props.hoverRow ? "pointer hover:bg-slate-200" : ""
);

/*
|-------------------------------------------------------------------------------
| Functions
|-------------------------------------------------------------------------------
*/

/**
 * Handle a row being clicked, the link function should return a string with
 * a valid URL to redirect to.
 */
const onRowClicked = (event: MouseEvent, item: T): void => {
    emit("row-clicked", event, item);

    if (props.linkFn) {
        let url = props.linkFn(item);
        handleLinkClick(event, url);
    }
};
</script>

<template>
    <div class="h-full">
        <ul class="rounded-lg border-collapse" :class="{ border: !noBorder }">
            <li v-if="!list.length">
                <slot name="empty-slot">
                    <h4 class="text-center">
                        {{ emptyText ?? "No Data" }}
                    </h4>
                </slot>
            </li>
            <li
                v-for="(item, index) in currentChunk"
                :key="index"
                class="border-collapse"
                :class="[
                    {
                        border: !noBorder,
                        'text-center': center,
                    },
                    paddingClass,
                    hoverClass,
                ]"
                @click="onRowClicked($event, item)"
            >
                <slot name="list-item" :item="item" :index="index">
                    {{ labelField ? item[labelField] : item }}
                </slot>
                <div class="float-end">
                    <slot name="actions" :item="item" />
                </div>
            </li>
        </ul>
        <div
            v-if="paginate && paginationData.length > 1"
            class="flex justify-center mt-2"
        >
            <Pagination
                :current-page="currentPage + 1"
                :total-pages="paginationData.length"
                @prev-page="currentPage--"
                @next-page="currentPage++"
                @go-to-page="goToPage"
            />
        </div>
    </div>
</template>
