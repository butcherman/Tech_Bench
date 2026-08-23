<script setup lang="ts" generic="TData extends string | object">
import { computed } from "vue";
import { useDataHelper } from "../composables/dataHelper";

const emit = defineEmits<{
    rowClicked: [event: MouseEvent, item: TData];
}>();

const props = defineProps<{
    list: TData[];
    textField?: TData extends string ? never : keyof TData;

    hasBorder?: boolean;
    compact?: boolean;
    emptyText?: string;
    hoverRow?: boolean;
    textPosition?: "start" | "end" | "center";

    rowClickFn?: (event: MouseEvent, item: TData) => void;
}>();

const { indexData } = useDataHelper();

const indexedList = computed(() => indexData(props.list));

const getListText = (listItem: TData) => {
    if (typeof listItem === "string") {
        return listItem;
    }

    if (props.textField) {
        return String(listItem[props.textField]);
    }

    return String(listItem);
};

const onRowClick = (event: MouseEvent, item: IndexedData<TData>) => {
    emit("rowClicked", event, item.data);

    if (props.rowClickFn) {
        props.rowClickFn(event, item.data);
    }
};

/*
|-------------------------------------------------------------------------------
| List Styling
|-------------------------------------------------------------------------------
*/
const emptyListText = computed(() => props.emptyText ?? "No Data");
const paddingClass = computed(() => (props.compact ? "p-1" : "p-3"));
const hoverClass = computed(
    () => props.hoverRow && "pointer hover:bg-slate-200",
);
const mainBorderClass = computed(
    () => props.hasBorder && "border border-slate-200 rounded-lg",
);
const itemBorderClass = computed(
    () => props.hasBorder && "border-b border-b-slate-300 last:border-b-0",
);
const textPlacement = computed(() => {
    return {
        start: "text-left",
        end: "text-right",
        center: "text-center",
    }[props.textPosition ?? "start"];
});
</script>

<template>
    <div>
        <ul :class="[mainBorderClass]">
            <li v-if="!indexedList.length">
                <slot name="empty-slot">
                    <h3 class="text-center text-muted">{{ emptyListText }}</h3>
                </slot>
            </li>
            <template v-for="item in indexedList" :key="item.id">
                <li
                    :class="[
                        paddingClass,
                        textPlacement,
                        hoverClass,
                        itemBorderClass,
                    ]"
                    @click="onRowClick($event, item)"
                >
                    <div class="flex">
                        <div class="grow">
                            <slot name="list-item" :item="item">
                                {{ getListText(item.data) }}
                            </slot>
                        </div>
                        <div>
                            <slot name="actions" :item="item" />
                        </div>
                    </div>
                </li>
            </template>
        </ul>
    </div>
</template>
