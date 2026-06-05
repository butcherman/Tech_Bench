<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Canvas from "@/Components/Workbook/WorkbookEditor/Canvas.vue";
import NodeDataEditor from "@/Components/Workbook/WorkbookEditor/NodeDataEditor.vue";
import NodeSelector from "@/Components/Workbook/WorkbookEditor/NodeSelector.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import { initWorkbook } from "@/Composables/Workbook/WorkbookEditor.module";
import { onMounted } from "vue";

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
            <NodeSelector />
            <Canvas class="md:col-span-3 h-full" />
        </div>
        <NodeDataEditor />
    </div>
</template>
