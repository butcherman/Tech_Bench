<script setup lang="ts">
import draggableComponent from "vuedraggable";
import { workbookData } from "@/Composables/Equipment/WorkbookEditor.module";
</script>

<template>
    <div
        class="relative border border-dashed border-slate-400 rounded-lg hover:border-dotted group/body p-1"
    >
        <div
            class="hidden text-xs absolute -top-4 right-4 border-t border-s border-e border-dashed border-slate-300 rounded-md px-1 text-muted group-hover/body:block"
        >
            Body
        </div>
        <draggableComponent
            :list="workbookData.body"
            :group="{ name: 'workbook', put: true }"
            class="h-full"
            item-key="index"
        >
            <template #item="{ element }">
                {{ element }}
            </template>
        </draggableComponent>
    </div>
</template>
