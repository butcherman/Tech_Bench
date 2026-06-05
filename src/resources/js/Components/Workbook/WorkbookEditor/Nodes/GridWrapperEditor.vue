<script setup lang="ts">
import draggableComponent from "vuedraggable";
import NodeOptions from "../NodeOptions.vue";
import NodeWrapper from "../NodeWrapper.vue";

const props = defineProps<{
    class: string;
    contents: workbookNode[];
    index: string;
}>();
</script>

<template>
    <div :class="class" class="relative group/wrapper">
        <NodeOptions
            class="hidden group-hover/wrapper:flex"
            :can-edit="false"
            :node-index="index"
        />
        <div
            v-for="container in contents"
            class="border border-slate-300 border-dashed rounded-md relative"
        >
            <draggableComponent
                item-key="page"
                :list="container.contents"
                :group="{ name: 'workbook', put: true }"
                class="h-full"
            >
                <template #item="{ element }">
                    <NodeWrapper :node="element" />
                </template>
            </draggableComponent>
        </div>
    </div>
</template>
