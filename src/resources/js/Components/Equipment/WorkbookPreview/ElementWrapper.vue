<script setup lang="ts">
import ElementData from "./ElementData.vue";
import ElementWrapper from "./ElementWrapper.vue";
import GridWrapper from "./GridWrapper.vue";

defineProps<{
    component: workbookEntry;
}>();
</script>

<template>
    <div>
        <template v-if="component.type === 'wrapper' && component.container">
            <component :is="component.tag" :class="component.class">
                <legend>{{ component.text }}</legend>
                <h4
                    v-if="!component.container?.length"
                    class="text-center text-muted opacity-50"
                >
                    Empty Container
                </h4>
                <div>
                    <template v-for="comp in component.container">
                        <GridWrapper
                            v-if="comp.type === 'grid-wrapper'"
                            :grid-row="comp"
                        />
                        <ElementWrapper v-else :component="comp" />
                    </template>
                </div>
            </component>
        </template>
        <template v-else>
            <ElementData :elem="component" />
        </template>
    </div>
</template>
