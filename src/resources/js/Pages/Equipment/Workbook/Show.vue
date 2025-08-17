<script setup lang="ts">
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";
import { dataGet } from "@/Composables/axiosWrapper.module";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    equipmentType: equipment;
}>();

const workbookData = reactive<workbookWrapper>({
    header: [],
    body: [],
    footer: [],
});

const activePage = ref("0");

/**
 * Retrieve the live version of the workbook including unsaved changes.
 */
const getWorkbook = async () => {
    console.log("getting workbook");
    await dataGet(route("workbooks.edit", props.equipmentType.equip_id)).then(
        (res) => {
            console.log(res);
            if (res) {
                workbookData.header = res.data.header;
                workbookData.body = res.data.body;
                workbookData.footer = res.data.footer;
            }
        }
    );
};

onMounted(() => {
    getWorkbook().then(() => (activePage.value = workbookData.body[0].page));
    Echo.private(`workbook-canvas.${props.equipmentType.equip_id}`)
        .listen(".workbookCanvasEvent", () => getWorkbook())
        .listenToAll((e) => console.log(e));
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
        <WorkbookBase
            class="grow"
            :workbook-data="workbookData"
            :active-page="activePage"
        />
    </div>
</template>
