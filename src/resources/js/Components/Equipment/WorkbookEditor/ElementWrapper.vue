<script setup lang="ts">
import draggableComponent from "vuedraggable";
defineProps<{
    component: workbookEntry;
}>();
</script>

<template>
    <template v-if="component.type === 'wrapper' && component.container">
        <component :is="component.tag" :class="component.class">
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
                                <ElementWrapper :component="element" />
                            </div>
                        </template>
                    </draggableComponent>
                </component>
            </template>
        </component>
    </template>
    <template v-else>
        <component
            :is="component.tag"
            :class="component.class"
            v-bind="component.props"
            v-html="component.text"
        />
    </template>
</template>
