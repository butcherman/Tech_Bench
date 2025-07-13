<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import WorkbookPreview from "@/Components/Equipment/WorkbookPreview/WorkbookPreview.vue";
import LinkLayout from "@/Layouts/FileLink/LinkLayout.vue";
import { computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    equipmentType: equipment;
    workbookData: workbookWrapper;
    customer: customer;
}>();

/**
 * Listen to live changes to the Workbook Data
 */
onMounted(() =>
    Echo.private(`workbook-canvas.${props.equipmentType.equip_id}`).listen(
        ".workbookCanvasEvent",
        () => {
            router.reload();
            console.log("preview update triggered");
        }
    )
);

/**
 * Refactor the Workbook Data to include any custom attributes.
 */
const liveWorkbook = computed(() => {
    let workbookCopy = JSON.stringify(props.workbookData, (key, val) => {
        if (key === "text") {
            if (val === "[ Customer Name ]") {
                return props.customer.name;
            }
        }

        return val;
    });

    return JSON.parse(workbookCopy);
});
</script>

<script lang="ts">
export default { layout: LinkLayout };
</script>

<template>
    <div class="grow flex flex-col h-full">
        <div class="mb-2">
            <h3 class="text-center">
                Preview Only. Changes made on this page will not be saved
            </h3>
            <h5 class="text-center">Save Workbook to Update Preview</h5>
            <p class="text-center">Close Page to Exit</p>
        </div>
        <Card
            :title="`Workbook Preview for ${equipmentType.name} (Using Randomly Generated Customer Data)`"
            class="grow"
        >
            <div class="grow flex flex-col">
                <WorkbookPreview :workbook-data="liveWorkbook" />
            </div>
        </Card>
    </div>
</template>
