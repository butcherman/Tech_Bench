<script setup lang="ts">
import {
    deleteElement,
    editElement,
} from "@/Composables/Equipment/WorkbookEditor";

defineProps<{
    elem: workbookElement;
    container: workbookElement[];
}>();
</script>

<template>
    <div class="group relative">
        <div class="hidden text-xs fixed end-8 group-hover:block pointer">
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
            :is="elem.tag"
            :class="elem.class"
            class="group-hover:border group-hover:border-green-300"
            v-bind="elem.props"
            v-html="elem.text"
        />
    </div>
</template>
