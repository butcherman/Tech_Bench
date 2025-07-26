<script setup lang="ts">
import ComponentListContainer from "./ComponentListContainer.vue";
import ComponentOptions from "./ComponentOptions.vue";
import EmptyContainer from "./EmptyContainer.vue";

defineProps<{
    element: workbookElement;
    container: workbookElement[];
}>();
</script>

<template>
    <div class="py-3 group/wrapper relative">
        <ComponentOptions
            :component="element"
            :container="container"
            class="hidden group-hover/wrapper:flex"
        />
        <component :is="element.tag" :class="element.class" class="relative">
            <legend>{{ element.text }}</legend>
            <template v-if="element.container">
                <EmptyContainer :isEmpty="element.container.length === 0" />
                <ComponentListContainer
                    :component-list="element.container"
                    class="min-h-20"
                />
            </template>
        </component>
    </div>
</template>
