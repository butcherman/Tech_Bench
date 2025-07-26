<script setup lang="ts">
import ComponentData from "./ComponentData.vue";
import draggableComponent from "vuedraggable";
import GridWrapper from "./GridWrapper.vue";
import FieldsetWrapper from "./FieldsetWrapper.vue";

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
            <FieldsetWrapper
                v-else-if="element.type === 'fieldset'"
                :element="element"
                :container="componentList"
            />
            <ComponentData
                v-else
                :element="element"
                :container="componentList"
            />
        </template>
    </draggableComponent>
</template>
