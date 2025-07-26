<script setup lang="ts">
import { computed } from "vue";
import {
    cloneComponent,
    deleteComponent,
    editComponent,
} from "@/Composables/Equipment/WorkbookEditor.module";

const props = defineProps<{
    component: workbookElement;
    container: workbookElement[];
}>();

const noEdit = ["static", "grid-wrapper", "wrapper"];

const canEdit = computed(() => !noEdit.includes(props.component.type));
</script>

<template>
    <div class="absolute end-0 -top-1 text-xs gap-1">
        <span
            v-if="canEdit"
            class="text-warning pointer"
            v-tooltip.bottom="'Edit Component Data'"
            @click="editComponent(component)"
        >
            <fa-icon icon="pencil" />
        </span>
        <span
            class="text-primary pointer"
            v-tooltip.bottom="'Clone Component'"
            @click="cloneComponent(component, container)"
        >
            <fa-icon icon="clone" />
        </span>
        <span
            class="text-danger pointer"
            v-tooltip.bottom="'Delete Component'"
            @click="deleteComponent(component, container)"
        >
            <fa-icon icon="trash-alt" />
        </span>
    </div>
</template>
