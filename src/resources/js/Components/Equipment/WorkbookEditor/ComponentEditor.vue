<script setup lang="ts">
import { Drawer } from "primevue";
import {
    editingComponent,
    onWbEditorClose,
    showWbEditor,
} from "@/Composables/Equipment/WorkbookEditor.module";
import { computed } from "vue";
import TextForm from "./ComponentForms/TextForm.vue";
import FormError from "./ComponentForms/FormError.vue";
import PageForm from "./ComponentForms/PageForm.vue";
import InputForm from "./ComponentForms/InputForm.vue";
import FieldsetForm from "./ComponentForms/FieldsetForm.vue";

const editingForm = computed(() => {
    if (!editingComponent.value) {
        return FormError;
    }

    if ("page" in editingComponent.value) {
        return PageForm;
    }

    if ("type" in editingComponent.value) {
        switch (editingComponent.value.type) {
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
        @after-hide="onWbEditorClose"
    >
        <component
            v-if="editingComponent"
            :is="editingForm"
            :component="editingComponent"
        />
    </Drawer>
</template>
