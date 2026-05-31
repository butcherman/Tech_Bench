<script setup lang="ts">
import draggableComponent from "vuedraggable";
import okModal from "@/Modules/okModal";
import { computed } from "vue";
import { workbookData } from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
import EmptyContainer from "./EmptyContainer.vue";

const props = defineProps<{
    isFooter?: boolean;
}>();

const name = computed(() => (props.isFooter ? "Footer" : "Header"));
const list = computed(() =>
    props.isFooter ? workbookData.footer : workbookData.header,
);

/**
 * Verify that the element dropped is allowed in the header
 */
const allowedInHeader: string[] = ["text", "static"];
const onHeaderDrop = (event: workbookDropEvent): void => {
    console.log("dropped", event);
    // if (event.added) {
    //     if (!allowedInHeader.includes(event.added.element.type)) {
    //         okModal(`Only Text Elements are allowed in the ${name.value}`);
    //         deleteElement(event.added.element, list.value);
    //     } else {
    //         imDirty();
    //     }
    // }
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
        <EmptyContainer :is-empty="list.length === 0" />
        <draggableComponent
            :list="list"
            :group="{ name: 'workbook', put: true }"
            class="min-h-20 flex flex-col gap-3"
            item-key="index"
            @change="onHeaderDrop"
        >
            <template #item="{ element }">
                {{ element }}
            </template>
        </draggableComponent>
    </div>
</template>
