<script setup lang="ts">
import FieldsetForm from "./ElementForms/FieldsetForm.vue";
import FormError from "./ElementForms/FormError.vue";
import InputForm from "./ElementForms/InputForm.vue";
import PageForm from "./ElementForms/PageForm.vue";
import TextForm from "./ElementForms/TextForm.vue";
import { computed } from "vue";
import { Drawer } from "primevue";
import {
    activeElement,
    clearActiveElement,
    showWbEditor,
} from "@/Composables/Equipment/WorkbookEditor.module";

/**
 * Determine which type of form should display in the Element Editor
 */
const editingForm = computed(() => {
    if (!activeElement.value) {
        return FormError;
    }

    // Page Data Elements
    if ("page" in activeElement.value) {
        return PageForm;
    }

    // Standard Text and Input Elements
    if ("type" in activeElement.value) {
        switch (activeElement.value.type) {
            case "text":
                return TextForm;
            case "input":
                return InputForm;
            case "fieldset":
                return FieldsetForm;
        }
    }

    return FormError;
});
</script>

<template>
    <Drawer
        v-model:visible="showWbEditor"
        position="right"
        header="Edit Component Data"
        @after-hide="clearActiveElement()"
    >
        <component
            v-if="activeElement"
            :is="editingForm"
            :element="activeElement"
        />
    </Drawer>
</template>
