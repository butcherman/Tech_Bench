<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import LinkLayout from "@/Layouts/FileLink/LinkLayout.vue";
import WorkbookEditor from "@/Components/Equipment/WorkbookEditor/WorkbookEditor.vue";
import { v4 } from "uuid";

const props = defineProps<{
    equipmentType: equipment;
}>();

const defaultWorkbook: workbookWrapper = {
    header: [
        {
            index: v4(),
            type: "static",
            tag: "h3",
            text: "[ Customer Name ]",
            class: "text-center",
        },
        {
            index: v4(),
            type: "static",
            tag: "h3",
            text: props.equipmentType.name,
            class: "text-center",
        },
    ],
    body: [
        {
            page: v4(),
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
    <div>
        <div class="mb-2">
            <BaseButton
                icon="arrow-left"
                text="Back to Tech Bench"
                :href="$route('workbooks.index')"
            />
        </div>
        <div>
            <WorkbookEditor
                :equipment-type="equipmentType"
                :workbook-data="defaultWorkbook"
            />
        </div>
    </div>
</template>
