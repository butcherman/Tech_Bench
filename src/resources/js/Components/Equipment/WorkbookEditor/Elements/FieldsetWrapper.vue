<script setup lang="ts">
import ElementOptions from "../Canvas/ElementOptions.vue";
import EmptyContainer from "./EmptyContainer.vue";
import ListContainer from "./ListContainer.vue";
import {
    cloneComponent,
    deleteElement,
    editElement,
} from "@/Composables/Equipment/WorkbookEditor.module";

defineProps<{
    element: workbookElement;
    container: workbookElement[];
}>();
</script>

<template>
    <div class="py-3 group/fieldset relative">
        <ElementOptions
            class="hidden group-hover/fieldset:flex"
            :can-edit="true"
            @edit="editElement(element)"
            @clone="cloneComponent(element, container)"
            @delete="deleteElement(element, container)"
        />
        <component :is="element.tag" :class="element.class" class="relative">
            <legend>{{ element.text }}</legend>
            <template v-if="element.container">
                <EmptyContainer :isEmpty="element.container.length === 0" />
                <ListContainer
                    :container-list="element.container"
                    class="min-h-20"
                />
            </template>
        </component>
    </div>
</template>
