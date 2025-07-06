<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import LinkLayout from "@/Layouts/FileLink/LinkLayout.vue";
import WorkbookEditor from "@/Components/Equipment/WorkbookEditor/WorkbookEditor.vue";
import { v4 } from "uuid";

defineProps<{
    equipmentType: equipment;
}>();

const defaultWorkbook: workbookWrapper = {
    header: [
        {
            index: v4(),
            type: "text",
            tag: "h1",
            text: "Header 1",
            class: "text-center",
        },
        {
            index: v4(),
            type: "static",
            tag: "h3",
            text: "[ Customer Name ]",
            class: "text-center",
        },
    ],
    body: [
        {
            page: "0",
            title: "Page 1",
            canPublish: true,
            container: [],
        },
    ],
    footer: [],
};
</script>

<script lang="ts">
export default { layout: LinkLayout };
</script>

<template>
    <div class="grow flex flex-col gap-2">
        <div>
            <BaseButton icon="arrow-left" text="Back to Tech Bench" />
        </div>
        <div class="grow flex">
            <WorkbookEditor
                :equipment-type="equipmentType"
                :workbook-data="defaultWorkbook"
            />
        </div>
    </div>
</template>
