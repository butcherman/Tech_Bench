<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import LinkLayout from "@/Layouts/FileLink/LinkLayout.vue";
import WorkbookPreview from "@/Components/Equipment/WorkbookPreview/WorkbookPreview.vue";
import { onMounted, ref } from "vue";
import { dataGet } from "@/Composables/axiosWrapper.module";

const props = defineProps<{
    equipmentType: equipment;
    customer: customer;
}>();

const workbookData = ref<workbookWrapper>();

/**
 * Get the current workbook data (includes unsaved changes)
 */
const getWorkbook = (): void => {
    dataGet(route("workbooks.edit", props.equipmentType.equip_id)).then(
        (res) => {
            if (res) {
                workbookData.value = JSON.parse(
                    JSON.stringify(res.data, (key, val) => {
                        if (key === "text") {
                            if (val === "[ Customer Name ]") {
                                return props.customer.name;
                            }
                        }

                        return val;
                    })
                );
            }
        }
    );
};

/**
 * Listen to live changes to the Workbook Data
 */
onMounted(() => {
    getWorkbook();
    Echo.private(`workbook-canvas.${props.equipmentType.equip_id}`).listen(
        ".workbookCanvasEvent",
        () => getWorkbook()
    );
});
</script>

<script lang="ts">
export default { layout: LinkLayout };
</script>

<template>
    <div class="grow flex flex-col h-full">
        <div class="mb-2">
            <h3 class="text-center">Preview Mode Only</h3>
            <p class="text-center">Close Page to Exit</p>
        </div>
        <Card
            :title="`Workbook Preview for ${equipmentType.name} (Using Randomly Generated Customer Data)`"
            class="grow"
        >
            <div class="grow flex flex-col">
                <WorkbookPreview
                    v-if="workbookData"
                    :workbook-data="workbookData"
                />
            </div>
        </Card>
    </div>
</template>
