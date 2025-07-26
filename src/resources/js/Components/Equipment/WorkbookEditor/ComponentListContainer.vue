<script setup lang="ts">
import draggableComponent from "vuedraggable";
import GridWrapper from "./GridWrapper.vue";

defineProps<{
    componentList: workbookElement[];
}>();
</script>

<template>
    <draggableComponent
        class="h-full"
        item-key="page"
        :list="componentList"
        :group="{ name: 'workbook', put: true }"
    >
        <template #item="{ element }">
            <GridWrapper
                v-if="element.type === 'grid-wrapper'"
                :grid-wrapper="element"
                :container="componentList"
            />
            <div v-else style="border: 1px solid red">
                {{ element }}
            </div>
        </template>
    </draggableComponent>
</template>
