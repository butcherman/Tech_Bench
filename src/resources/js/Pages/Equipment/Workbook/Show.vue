<script setup lang="ts">
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import WorkbookWrapper from "@/Components/Workbook/WorkbookWrapper.vue";
import { dataGet } from "@/Composables/axiosWrapper.module";
import { reactive, onMounted } from "vue";
import {
    activePage,
    isPreviewMode,
} from "@/Composables/Workbook/CustomerWorkbook.module";

const props = defineProps<{
    equipmentType: equipment;
}>();

const workbookSkeleton = reactive<workbookWrapper>({
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
                workbookSkeleton.header = res.data.header;
                workbookSkeleton.body = res.data.body;
                workbookSkeleton.footer = res.data.footer;
            }
        },
    );
};

/**
 * Get the workbook skeleton and register to listen for WB changes
 */
onMounted(() => {
    isPreviewMode.value = true;
    getWorkbookSkeleton().then(
        () => (activePage.value = workbookSkeleton.body[0].page),
    );

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
        <WorkbookWrapper class="grow" :workbook-skeleton="workbookSkeleton" />
    </div>
</template>
