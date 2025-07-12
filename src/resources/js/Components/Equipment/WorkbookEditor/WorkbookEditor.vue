<script setup lang="ts">
import FieldsetElement from "./ElementEditors/FieldsetElement.vue";
import InputElement from "./ElementEditors/InputElement.vue";
import PageElement from "./ElementEditors/PageElement.vue";
import TextElement from "./ElementEditors/TextElement.vue";
import WorkbookCanvas from "./WorkbookCanvas.vue";
import WorkbookComponents from "./WorkbookComponents.vue";
import { computed, onMounted } from "vue";
import { Drawer } from "primevue";
import {
    editingElement,
    editingPage,
    setWorkbookData,
    showEditor,
} from "@/Composables/Equipment/WorkbookEditor";

const props = defineProps<{
    equipmentType: equipment;
    workbookData: workbookWrapper;
}>();

onMounted(() => setWorkbookData(props.workbookData, props.equipmentType));

const editingComponent = computed(() => {
    switch (editingElement.value?.type) {
        case "text":
            return TextElement;
        case "input":
            return InputElement;
        case "wrapper":
            return FieldsetElement;
    }

    return TextElement;
});

const onDrawerClose = () => {
    editingPage.value = undefined;
    editingElement.value = undefined;
};
</script>

<template>
    <div class="flex grow gap-2">
        <div class="basis-1/4">
            <WorkbookComponents />
        </div>
        <div class="grow">
            <WorkbookCanvas />
        </div>
    </div>
    <Drawer
        v-model:visible="showEditor"
        position="right"
        header="Edit Element"
        @after-hide="onDrawerClose()"
    >
        <PageElement v-if="editingPage" :page="editingPage" />
        <component
            v-if="editingElement"
            :is="editingComponent"
            :element="editingElement"
        />
    </Drawer>
</template>
