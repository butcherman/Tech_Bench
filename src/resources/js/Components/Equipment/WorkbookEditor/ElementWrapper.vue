<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementData from "./ElementData.vue";

defineProps<{
    component: workbookEntry;
    container: workbookEntry[];
}>();
</script>

<template>
    <template v-if="component.type === 'wrapper' && component.container">
        <h1>Is Wrapper</h1>
        <!-- <component :is="component.tag" :class="component.class">
            <template v-for="comp in component.container">
                <component :is="comp.tag" :class="comp.class">
                    <legend v-if="comp.tag === 'fieldset'">
                        {{ comp.text }}
                    </legend>
                    <draggableComponent
                        :list="comp.container"
                        :group="{ name: 'workbook', put: true }"
                        item-key="index"
                    >
                        <template #item="{ element }">
                            <div>
                                <ElementWrapper
                                    :component="element"
                                    :container="container"
                                />
                            </div>
                        </template>
                    </draggableComponent>
                </component>
            </template>
        </component> -->
    </template>
    <template v-else>
        <ElementData :elem="component" :container="container" />
    </template>
</template>
