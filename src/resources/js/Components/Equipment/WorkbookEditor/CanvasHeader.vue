<script setup lang="ts">
import draggableComponent from "vuedraggable";
import okModal from "@/Modules/okModal";
import { workbookData } from "@/Composables/Equipment/WorkbookEditor.module";
import { computed } from "vue";

const props = defineProps<{
    isFooter?: boolean;
}>();

/**
 * Only these element 'types' are allowed to be dropped in the header.
 */
const allowedInHeader: string[] = ["text", "static"];

const group = computed<string>(() => (props.isFooter ? "footer" : "header"));
const name = computed<string>(() => (props.isFooter ? "Footer" : "Header"));
const isEmpty = computed<boolean>(() => {
    if (props.isFooter) {
        return workbookData.footer.length === 0;
    }

    return workbookData.header.length === 0;
});

/**
 * Verify that the element dropped is allowed in the header.
 */
const onHeaderDrop = (event: workbookDropEvent) => {
    if (event.added) {
        if (!allowedInHeader.includes(event.added.element.type)) {
            okModal(
                "Only Text Elements are Allowed in Header and Footer Sections"
            );
            if (props.isFooter) {
                workbookData.footer.splice(event.added.newIndex, 1);
            } else {
                workbookData.header.splice(event.added.newIndex, 1);
            }
        }
    }
};
</script>

<template>
    <div
        class="relative border border-dashed border-slate-400 rounded-lg hover:border-dotted p-1"
        :class="`group/${group}`"
    >
        <div
            class="hidden text-xs absolute -top-4 right-4 border-t border-s border-e border-dashed border-slate-300 rounded-md px-1 text-muted"
            :class="`group-hover/${group}:block`"
        >
            {{ name }}
        </div>
        <div v-if="isEmpty" class="absolute top-5 w-full">
            <h4 class="text-center text-muted opacity-50">Drag Element Here</h4>
        </div>
        <draggableComponent
            :list="isFooter ? workbookData.footer : workbookData.header"
            :group="{ name: 'workbook', put: true }"
            class="min-h-20"
            item-key="index"
            @change="onHeaderDrop"
        >
            <template #item="{ element }">
                {{ element }}
            </template>
        </draggableComponent>
    </div>
</template>
