<script setup lang="ts">
import FieldsetForm from "./NodeForms/FieldsetForm.vue";
import FormError from "./NodeForms/FormError.vue";
import PageForm from "./NodeForms/PageForm.vue";
import { computed } from "vue";
import { Drawer } from "primevue";
import {
    activeNode,
    showNodeEditor,
} from "@/Composables/Workbook/Canvas/WorkbookEditor.module";

/**
 * Title of the Editor Page
 */
const editorHeader = computed<string>(() => {
    if (!activeNode.value) {
        return "Edit Data";
    }

    if ("page" in activeNode.value) {
        return "Edit Page Data";
    }

    return "Edit Element Data";
});

/**
 * Determine which form should show in the Editor Page
 */
const editingForm = computed(() => {
    if (!activeNode.value) {
        return FormError;
    }

    if ("page" in activeNode.value) {
        return PageForm;
    }

    switch (activeNode.value.type) {
        case "fieldset":
            return FieldsetForm;
    }

    return FormError;
});
</script>

<template>
    <Drawer
        v-model:visible="showNodeEditor"
        class="!w-3/4"
        position="right"
        :header="editorHeader"
    >
        <component v-if="activeNode" :is="editingForm" :node="activeNode" />
    </Drawer>
</template>
