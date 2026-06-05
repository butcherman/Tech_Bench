<script setup lang="ts">
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";
import { reactive, onMounted } from "vue";
import { isPreviewMode } from "@/Composables/Workbook/CustomerWorkbook.module";
import { dataGet } from "@/Composables/axiosWrapper.module";

const props = defineProps<{
    equipmentType: equipment;
}>();

const workbookData = reactive<workbookWrapper>({
    header: [],
    body: [],
    footer: [],
});

/**
 * Pull the current working version of the workbook inluding unsaved changes.
 */
const getWorkbookSkeleton = async () => {
    await dataGet(route("workbooks.edit", props.equipmentType.equip_id)).then(
        (res) => {
            if (res) {
                workbookData.header = res.data.header;
                workbookData.body = res.data.body;
                workbookData.footer = res.data.footer;
            }
        },
    );
};

/**
 * Get the workbook skeleton and register to listen for WB changes
 */
onMounted(() => {
    isPreviewMode.value = true;
    getWorkbookSkeleton();

    Echo.private(`workbook-canvas.${props.equipmentType.equip_id}`).listen(
        ".workbookCanvasEvent",
        () => getWorkbookSkeleton(),
    );
});
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex grow flex-col">
        <div>
            <h3 class="text-center">Preview Mode</h3>
            <p class="text-center">Close Page to Exit</p>
        </div>
        <WorkbookBase class="grow" :workbook-data="workbookData" />
    </div>
</template>
