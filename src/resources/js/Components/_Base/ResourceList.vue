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
                v-for="(item, index) in list"
                :key="index"
                class="p-3 border-collapse"
                :class="{
                    border: !noBorder,
                    'text-center': center,
                    'pointer hover:bg-slate-200': linkFn,
                }"
                @click="onRowClicked($event, item)"
            >
                <slot name="list-item" :item="item">
                    {{ item[labelField] }}
                </slot>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts" generic="T">
import { handleLinkClick } from "../../Composables/links.module";

const emit = defineEmits<{
    "row-clicked": [event: MouseEvent, item: T];
}>();

const props = defineProps<{
    center?: boolean;
    emptyText?: string;
    labelField: keyof T;
    list: T[];
    noBorder?: boolean;
    linkFn?: (row: any) => string;
}>();

const onRowClicked = (event: MouseEvent, item: T): void => {
    emit("row-clicked", event, item);

    if (props.linkFn) {
        let url = props.linkFn(item);
        handleLinkClick(event, url);
    }
};
</script>
