<script setup lang="ts">
import ElementOptions from "../Canvas/ElementOptions.vue";
import { computed, defineAsyncComponent } from "vue";
import {
    cloneComponent,
    deleteElement,
    editElement,
    noEditTypes,
} from "@/Composables/Equipment/WorkbookEditor.module";

const props = defineProps<{
    element: workbookElement;
    container: workbookElement[];
}>();

/**
 * Download the Async Component
 */
const component = computed(() => {
    if (props.element.component) {
        return defineAsyncComponent(
            () =>
                import(`../../../../Forms/_Base/${props.element.component}.vue`)
        );
    }

    // If the component is just an HTML Tag
    return props.element.tag;
});

/**
 * Determine if the element can be edited
 */

const canEdit = computed(() => !noEditTypes.includes(props.element.type));
</script>

<template>
    <div class="relative py-1 group/data">
        <ElementOptions
            class="hidden group-hover/data:flex"
            :can-edit="canEdit"
            @edit="editElement(element)"
            @clone="cloneComponent(element, container)"
            @delete="deleteElement(element, container)"
        />
        <component
            :is="component"
            :id="element.index"
            :name="element.index"
            :class="element.class"
            v-bind="element.props"
            v-html="element.text"
            class="group-hover/data:border group-hover/data:border-green-200 py-1"
        />
    </div>
</template>
