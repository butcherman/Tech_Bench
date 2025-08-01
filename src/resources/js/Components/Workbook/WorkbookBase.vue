<script setup lang="ts">
import Card from "../_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import { provide } from "vue";
import { useForm } from "vee-validate";
import { dataPut } from "@/Composables/axiosWrapper.module";

const props = defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
    isPreview?: boolean;
    values?: { [index: string]: string };
    workbookHash?: string;
}>();

const { values } = useForm({
    initialValues: props.values,
});

/**
 * Save an individual field
 */
const triggerSave = (index: string): void => {
    if (props.workbookHash) {
        let fieldValue = values[index];

        dataPut(route("customer-workbook.update", props.workbookHash), {
            index,
            fieldValue,
        }).then((res) => {
            console.log(res);
        });
    }
};

provide("triggerSave", triggerSave);
</script>

<template>
    <Card>
        <form novalidate @submit.prevent class="h-full flex flex-col">
            <WorkbookHeader :data="workbookData.header" />
            <WorkbookBody
                class="grow"
                :workbook-data="workbookData"
                :active-page="activePage"
                :values="values"
            />
            <WorkbookHeader :data="workbookData.footer" />
        </form>
    </Card>
</template>
