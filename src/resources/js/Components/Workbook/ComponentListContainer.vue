<script setup lang="ts">
import ComponentData from "./ComponentData.vue";
import FieldsetWrapper from "./FieldsetWrapper.vue";
import GridWrapper from "./GridWrapper.vue";

defineProps<{
    componentList: workbookElement[];
}>();
</script>

<template>
    <div>
        <template v-for="element in componentList">
            <GridWrapper
                v-if="element.type === 'grid-wrapper'"
                :grid-wrapper="element"
                tabindex="-1"
            />
            <FieldsetWrapper
                v-else-if="element.type === 'fieldset'"
                :element="element"
                tabindex="-1"
            />
            <ComponentData v-else :element="element" />
        </template>
    </div>
</template>
