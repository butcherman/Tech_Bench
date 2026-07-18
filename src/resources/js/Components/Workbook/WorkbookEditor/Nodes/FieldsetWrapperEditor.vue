<script setup lang="ts">
import draggableComponent from "vuedraggable";
import NodeOptions from "../NodeOptions.vue";
import NodeWrapper from "../NodeWrapper.vue";

const props = defineProps<{
    class: string;
    contents: workbookNode[];
    index: string;
    text: string | null;
}>();
</script>

<template>
    <div class="relative group/wrapper">
        <NodeOptions
            class="hidden group-hover/wrapper:flex"
            :can-edit="true"
            :node-index="index"
        />
        <fieldset :class="class">
            <legend>{{ text }}</legend>
            <draggableComponent
                item-key="page"
                class="h-full min-h-20"
                :list="contents"
                :group="{ name: 'workbook', put: true }"
            >
                <template #item="{ element }">
                    <NodeWrapper :node="element" />
                </template>
            </draggableComponent>
        </fieldset>
    </div>
</template>
