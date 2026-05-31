<script setup lang="ts">
import draggableComponent from "vuedraggable";

const props = defineProps<{
    nodeList: workbookNode[];
}>();
</script>

<template>
    <draggableComponent
        class="h-full"
        item-key="page"
        :list="nodeList"
        :group="{ name: 'workbook', put: true }"
    >
        <template #item="{ element }">
            {{ element }}
        </template>
    </draggableComponent>
</template>
