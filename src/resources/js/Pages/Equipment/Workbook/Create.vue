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
            container: [
                {
                    index: v4(),
                    type: "wrapper",
                    tag: "div",
                    class: "grid grid-cols-2 gap-2",
                    container: [
                        {
                            index: v4(),
                            type: "wrapper",
                            tag: "fieldset",
                            text: "fieldset text",
                            class: "border border-slate-300 rounded-lg p-2",
                            container: [
                                {
                                    index: v4(),
                                    type: "input",
                                    tag: "input",
                                    class: "w-full border border-slate-300",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: "",
                                        value: "",
                                    },
                                },
                                {
                                    index: v4(),
                                    type: "input",
                                    tag: "input",
                                    class: "w-full border border-slate-300",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: "",
                                        value: "",
                                    },
                                },
                            ],
                        },
                        {
                            index: v4(),
                            type: "wrapper",
                            tag: "fieldset",
                            class: "border border-slate-300 rounded-lg",
                            container: [
                                {
                                    index: v4(),
                                    type: "input",
                                    tag: "input",
                                    class: "w-full border border-slate-400 rounded-lg px-2",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: "",
                                        value: "",
                                    },
                                },
                            ],
                        },
                    ],
                },
            ],
        },
        {
            page: v4(),
            title: "Page 2",
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
