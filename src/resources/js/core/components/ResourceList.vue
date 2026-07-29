<script setup lang="ts" generic="TData">
import { computed } from "vue";
import { useDataHelper } from "../composables/dataHelper";

const props = defineProps<{
    data: TData[];

    compact?: boolean;
    textField?: string;
    noResultsText?: string;
    paginate?: string;
    borderless?: boolean;
    hoverRow?: boolean;
    striped?: boolean;
    centerItems?: boolean;

    rowClassFn?: (row: TData) => string | false;
    rowLinkFn?: (event: MouseEvent, row: TData) => void;
}>();

const { indexData } = useDataHelper();

const indexedData = computed<IndexedData<TData>[]>(() => indexData(props.data));
const emptyText = computed<string>(() => props.noResultsText ?? "No Data");
</script>

<template>
    <ul
        class="flex flex-col gap-2 border-slate-300 rounded-lg border-collapse p-2"
        :class="{ border: !borderless }"
    >
        <li v-if="!data.length" class="text-muted">
            <slot name="empty-slot">
                <h4 class="text-center">{{ emptyText }}</h4>
            </slot>
        </li>
        <li v-for="row in indexedData" :key="row.id">
            {{ row }}
        </li>
    </ul>
</template>
