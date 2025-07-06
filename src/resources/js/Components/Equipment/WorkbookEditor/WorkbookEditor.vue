<script setup lang="ts">
import TextElement from "./ElementEditors/TextElement.vue";
import WorkbookCanvas from "./WorkbookCanvas.vue";
import WorkbookComponents from "./WorkbookComponents.vue";
import { onMounted } from "vue";
import { Drawer } from "primevue";
import {
    editingElement,
    setWorkbookData,
    showEditor,
} from "@/Composables/Equipment/WorkbookEditor";

const props = defineProps<{
    equipmentType: equipment;
    workbookData: workbookWrapper;
}>();

onMounted(() => setWorkbookData(props.workbookData, props.equipmentType));
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
    <Drawer v-model:visible="showEditor" position="right" header="Edit Element">
        <template v-if="editingElement">
            <div v-if="editingElement.type === 'text'">
                <TextElement :element="editingElement" />
            </div>
            <div v-else>
                <p class="text-center text-muted">No Editable Fields</p>
            </div>
        </template>
    </Drawer>
</template>
