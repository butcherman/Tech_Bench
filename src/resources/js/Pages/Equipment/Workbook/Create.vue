<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import CanvasComponents from "@/Components/Equipment/WorkbookEditor/CanvasComponents.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import { Drawer } from "primevue";
import {
    onWbEditorClose,
    showWbEditor,
} from "@/Composables/Equipment/WorkbookEditor.module";

defineProps<{
    equipmentType: equipment;
    workbookData: workbookWrapper;
}>();
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="mb-2">
            <BaseButton
                icon="arrow-left"
                text="Back to Tech Bench"
                :href="$route('workbooks.index')"
            />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 grow">
            <div>
                <CanvasComponents />
            </div>
            <div class="md:col-span-3 h-full" style="border: 1px solid red">
                workbook canvas
            </div>
        </div>
        <Drawer
            v-model:visible="showWbEditor"
            position="right"
            header="Edit Element Data"
            @after-hide="onWbEditorClose"
        >
            Editing Component
        </Drawer>
    </div>
</template>
