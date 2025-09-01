<script setup lang="ts">
import ElementData from "./ElementData.vue";
import FieldsetWrapper from "./FieldsetWrapper.vue";
import GridWrapper from "./GridWrapper.vue";

defineProps<{
    elementList: workbookElement[];
}>();
</script>

<template>
    <div>
        <div v-if="elementList.length === 0">
            <h5 class="text-center text-muted opactity-50 text-muted">
                No Data
            </h5>
        </div>
        <template v-for="element in elementList">
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
            <ElementData v-else :element="element" />
        </template>
    </div>
</template>
