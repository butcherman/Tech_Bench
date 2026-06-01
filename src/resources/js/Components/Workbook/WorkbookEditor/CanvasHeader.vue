<script setup lang="ts">
import draggableComponent from "vuedraggable";
import EmptyContainer from "./EmptyContainer.vue";
import okModal from "@/Modules/okModal/index.js";
import NodeWrapper from "./NodeWrapper.vue";
import { computed } from "vue";
import {
    deleteNode,
    imDirty,
    workbookData,
} from "@/Composables/Workbook/Canvas/WorkbookEditor.module.js";

const props = defineProps<{
    isFooter?: boolean;
}>();

const name = computed(() => (props.isFooter ? "Footer" : "Header"));
const contents = computed(() =>
    props.isFooter ? workbookData.footer : workbookData.header,
);

// Only specific types are allowed to be dropped in the Header
const allowedInHeader: string[] = ["text", "static"];
const onHeaderDrop = (event: workbookDropEvent): void => {
    if (event.added) {
        if (!allowedInHeader.includes(event.added.element.type)) {
            okModal(`Only Text Elements are allowed in the ${name.value}`);
            deleteNode(event.added.element, contents.value);
        } else {
            imDirty();
        }
    }
};
</script>

<template>
    <div
        class="min-h-20 relative rounded-lg border border-dashed border-slate-400 hover:border-dotted group/header p-1"
    >
        <div
            class="absolute -top-5 right-4 rounded-t-md border-t border-s border-e border-dotted border-slate-400 py-0 px-1 text-xs text-muted hidden group-hover/header:block"
        >
            {{ name }}
        </div>
        <EmptyContainer :is-empty="contents.length === 0" />
        <draggableComponent
            class="grow"
            :list="contents"
            :group="{
                name: 'workbook',
                put: true,
            }"
            item-key="index"
            @change="onHeaderDrop"
        >
            <template #item="{ element }">
                <NodeWrapper :node="element" />
            </template>
        </draggableComponent>
    </div>
</template>
