<script setup lang="ts">
import Card from "../_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import { isLoading } from "@/Composables/axiosWrapper.module.js";
import { computed, onMounted, provide } from "vue";
import { useForm } from "vee-validate";
import {
    hasError,
    isPagePublic,
    saveWorkbookValue,
    wbHash,
} from "@/Composables/Workbook/CustomerWorkbook.module.js";

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

/**
 * Vee-Validate functions and values
 */
const { values, setFieldValue } = useForm({
    name: "workbook",
    initialValues: props.workbookValues,
});

const saveFieldValue = (index: string): void => {
    let saveData: workbookSaveData = {
        index,
        value: values[index],
        public: isPagePublic.value,
        isTable: false,
    };

    saveWorkbookValue(saveData);
};

const saveTableCell = (
    arrayIndex: number,
    tableIndex: string,
    rowIndex: string,
    columnName: string,
): void => {
    let saveData: workbookSaveData = {
        table_index: tableIndex,
        row_index: rowIndex,
        column_name: columnName,
        value: values[tableIndex][arrayIndex][columnName],
        public: isPagePublic.value,
        isTable: true,
    };

    saveWorkbookValue(saveData);
};

provide("saveFieldValue", saveFieldValue);
provide("saveTableCell", saveTableCell);

onMounted(() => {
    /**
     * Register for live updates to the workbook data
     */
    Echo.channel(`equipment-workbook.${wbHash.value}`).listen(
        ".WorkbookValueUpdated",
        (valData: workbookValueEvent) => {
            console.log(valData);
            setFieldValue(valData.model.index, valData.model.value);
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
