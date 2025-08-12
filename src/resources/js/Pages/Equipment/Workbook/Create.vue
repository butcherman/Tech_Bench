<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import CanvasElements from "@/Components/Equipment/WorkbookEditor/CanvasElements.vue";
import ElementDataEditor from "@/Components/Equipment/WorkbookEditor/ElementDataEditor.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import WorkbookCanvas from "@/Components/Equipment/WorkbookEditor/WorkbookCanvas.vue";
import { onMounted } from "vue";
import { initWorkbook } from "@/Composables/Equipment/WorkbookEditor.module";

const props = defineProps<{
    equipmentType: equipment;
    workbookData: workbookWrapper;
}>();

/**
 * Initialize the workbook canvas
 */
onMounted(() => initWorkbook(props.equipmentType, props.workbookData));
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
                <CanvasElements />
            </div>
            <div class="md:col-span-3 h-full">
                <WorkbookCanvas />
            </div>
        </div>
        <!-- <ElementDataEditor /> -->
    </div>
</template>
