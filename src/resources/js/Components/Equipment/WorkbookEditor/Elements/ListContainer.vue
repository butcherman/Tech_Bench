<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementData from "./ElementData.vue";
import FieldsetWrapper from "./FieldsetWrapper.vue";
import GridWrapper from "./GridWrapper.vue";

defineProps<{
    containerList: workbookElement[];
}>();
</script>

<template>
    <draggableComponent
        class="h-full"
        item-key="page"
        :list="containerList"
        :group="{ name: 'workbook', put: true }"
    >
        <template #item="{ element }">
            <GridWrapper
                v-if="element.type === 'grid-wrapper'"
                :grid-wrapper="element"
                :container="containerList"
            />
            <FieldsetWrapper
                v-else-if="element.type === 'fieldset'"
                :element="element"
                :container="containerList"
            />
            <ElementData v-else :element="element" :container="containerList" />
        </template>
    </draggableComponent>
</template>
