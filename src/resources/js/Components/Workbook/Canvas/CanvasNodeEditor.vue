<script setup lang="ts">
import FormError from "./NodeForms/FormError.vue";
import PageForm from "./NodeForms/PageForm.vue";
import { Drawer } from "primevue";
import {
    activeNode,
    clearActiveNode,
    showNodeEditor,
} from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
import { computed } from "vue";

const editorHeader = computed(() => {
    if (!activeNode.value) {
        return "Edit Data";
    }

    if ("page" in activeNode.value) {
        return "Edit Page Data";
    }

    return "Edit Element Data";
});

const editingForm = computed(() => {
    if (!activeNode.value) {
        return FormError;
    }

    if ("page" in activeNode.value) {
        return PageForm;
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
        @after-hide="clearActiveNode()"
    >
        <component v-if="activeNode" :is="editingForm" :node="activeNode" />
    </Drawer>
</template>
