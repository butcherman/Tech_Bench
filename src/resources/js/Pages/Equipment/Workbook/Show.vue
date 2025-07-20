<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import { reactive, onMounted } from "vue";
import { dataGet } from "@/Composables/axiosWrapper.module";

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
    <div>
        <Card>
            {{ workbookData }}
        </Card>
    </div>
</template>
