<template>
    <div class="h-full">
        <ul class="rounded-lg border-collapse" :class="{ border: !noBorder }">
            <li v-if="!list.length">
                <slot name="empty-slot">
                    <h5 class="text-center text-muted">
                        {{ emptyText ?? "No Data" }}
                    </h5>
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
                {{ item[labelField] }}
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
    list: T[];
    labelField: keyof T;
    center?: boolean;
    emptyText?: string;
    noBorder?: boolean;
    linkFn?: (row: any) => string;
}>();

const onRowClicked = (event: MouseEvent, item: T) => {
    emit("row-clicked", event, item);

    if (props.linkFn) {
        let url = props.linkFn(item);
        handleLinkClick(event, url);
    }
};
</script>
