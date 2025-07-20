<script setup lang="ts">
import { computed, defineAsyncComponent } from "vue";
import {
    cloneComponent,
    deleteComponent,
    editComponent,
} from "@/Composables/Equipment/WorkbookEditor.module";

const props = defineProps<{
    element: workbookElement;
    container: workbookElement[];
}>();

const component = computed(() => {
    if (props.element.component) {
        return defineAsyncComponent(
            () => import(`../../../Forms/_Base/${props.element.component}.vue`)
        );
    }

    return props.element.tag;
});
</script>

<template>
    <div class="hover:border hover:border-green-300 p-1 mx-4 relative group">
        <div class="absolute end-0 text-xs gap-1 hidden group-hover:flex">
            <span
                class="text-warning pointer"
                v-tooltip.bottom="'Edit Component Data'"
                @click="editComponent(element)"
            >
                <fa-icon icon="pencil" />
            </span>
            <span
                class="text-primary pointer"
                v-tooltip.bottom="'Clone Component'"
                @click="cloneComponent(element, container)"
            >
                <fa-icon icon="clone" />
            </span>
            <span
                class="text-danger pointer"
                v-tooltip.bottom="'Delete Component'"
                @click="deleteComponent(element, container)"
            >
                <fa-icon icon="trash-alt" />
            </span>
        </div>
        <component
            :is="component"
            :id="element.index"
            :name="element.index"
            :class="element.class"
            v-bind="element.props"
            v-html="element.text"
        />
    </div>
</template>
