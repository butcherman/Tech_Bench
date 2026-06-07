<script setup lang="ts">
import Card from "../_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import { computed, onMounted } from "vue";
import { useForm } from "vee-validate";
import {
    hasError,
    wbHash,
} from "@/Composables/Workbook/CustomerWorkbook.module.js";
import { isLoading } from "@/Composables/axiosWrapper.module.js";

const props = defineProps<{
    workbookSkeleton: workbookWrapper;
    workbookValues: { [key: string]: any };
}>();

/**
 * Icon to show during loading and idle periods
 */
const saveIcon = computed<string>(() => {
    if (hasError.value) {
        return "triangle-exclamation";
    }

    if (isLoading.value) {
        return "spinner";
    }

    return "circle-check";
});

/**
 * Font color of icon during loading and idle periods
 */
const saveClass = computed<string>(() => {
    if (hasError.value) {
        return "text-danger";
    }

    if (isLoading.value) {
        return "text-warning fa-spin";
    }

    return "text-success";
});

const { values, setFieldValue } = useForm({
    name: "workbook",
    initialValues: props.workbookValues,
});

onMounted(() => {
    Echo.channel(`equipment-workbook.${wbHash.value}`)
        .listen(".WorkbookValueUpdated", (valData: workbookValueEvent) => {
            setFieldValue(valData.model.index, valData.model.value);
            console.log(valData.model.index, valData.model.value);
        })
        .listen(
            ".WorkbookTableValueUpdated",
            (valData: workbookTableValueEvent) => {
                let updatedModel = valData.model;
                let table = { ...values[updatedModel.table_index] };

                let rowCopy = { ...table[updatedModel.row_index] };
                rowCopy[updatedModel.column_name] = updatedModel.value;
                table[updatedModel.row_index] = rowCopy;

                setFieldValue(updatedModel.table_index, table);
            },
        );
});
</script>

<template>
    <Card class="h-full">
        <div class="flex flex-col h-full">
            <WorkbookHeader :header-skeleton="workbookSkeleton.header" />
            <form class="grow" novalidate v-focustrap @submit.prevent>
                <WorkbookBody :body-skeleton="workbookSkeleton.body" />
            </form>
            <WorkbookHeader :header-skeleton="workbookSkeleton.footer" />
        </div>
        <template #footer>
            <div class="flex flex-row-reverse">
                <span v-tooltip.left="'Loading Status'">
                    <fa-icon :icon="saveIcon" :class="saveClass" />
                </span>
            </div>
        </template>
    </Card>
</template>
