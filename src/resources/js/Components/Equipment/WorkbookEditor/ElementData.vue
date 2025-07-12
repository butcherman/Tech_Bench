<script setup lang="ts">
import { computed, defineAsyncComponent } from "vue";
import {
    deleteElement,
    editElement,
} from "@/Composables/Equipment/WorkbookEditor";

const props = defineProps<{
    elem: workbookElement;
    container: workbookElement[];
}>();

const theComponent = computed(() => {
    if (props.elem.component) {
        return defineAsyncComponent(
            () => import(`../../../Forms/_Base/${props.elem.component}.vue`)
        );
    }

    return props.elem.tag;
});
</script>

<template>
    <div class="group relative">
        <div
            class="hidden text-xs absolute end-0 group-hover:block pointer z-20"
        >
            <span
                v-if="elem.type !== 'static'"
                class="text-warning me-2"
                v-tooltip="'Edit'"
                @click="editElement(elem)"
            >
                <fa-icon icon="pencil" />
            </span>
            <span
                class="text-danger"
                v-tooltip="'Delete'"
                @click="deleteElement(elem, container)"
            >
                <fa-icon icon="trash-alt" />
            </span>
        </div>
        <component
            :is="theComponent"
            :id="elem.index"
            :name="elem.index"
            :class="elem.class"
            class="group-hover:border group-hover:border-green-300"
            v-bind="elem.props"
            v-html="elem.text"
        />
    </div>
</template>
