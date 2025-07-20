<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import { reactive, onMounted } from "vue";
import { dataGet } from "@/Composables/axiosWrapper.module";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";

const props = defineProps<{
    equipmentType: equipment;
}>();

const workbookData = reactive({
    header: [],
    body: [],
    footer: [],
});

const getWorkbook = () => {
    dataGet(route("workbooks.edit", props.equipmentType.equip_id)).then(
        (res) => {
            if (res) {
                workbookData.header = res.data.header;
                workbookData.body = res.data.body;
                workbookData.footer = res.data.footer;
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
export default { layout: PublicLayout };
</script>

<template>
    <div class="grow flex flex-col">
        <div>
            <h3 class="text-center">Preview Mode</h3>
            <p class="text-center">Close Page to Exit</p>
        </div>
        <WorkbookBase class="grow" :workbook-data="workbookData" is-preview />
    </div>
</template>
