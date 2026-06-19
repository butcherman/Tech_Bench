<script setup lang="ts">
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import ImportTableDataForm from "@/Forms/Workbook/ImportTableDataForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ValidationResults from "./ValidationResults.vue";
import { wbHash } from "@/Composables/Workbook/CustomerWorkbook.module";
import { dataGet, dataPut } from "@/Composables/axiosWrapper.module";
import { computed, onMounted, ref, useTemplateRef } from "vue";
import type { AxiosResponse } from "axios";

const props = defineProps<{
    tableIndex: string;
}>();

const importModal = useTemplateRef("import-data-modal");

const loadingMessage = ref<string>();
const isLoading = ref<boolean>(false);
const showUploadForm = ref<boolean>(false);
const showValidationResults = ref<boolean>(false);
const validatedResults = ref<workbookValidationData[]>();

const fileIsValid = computed<boolean>(() => {
    if (validatedResults.value) {
        let valid: boolean = true;

        // Check if any of the table fields are invalid
        validatedResults.value.forEach((row) => {
            let values = Object.values(row);
            values.forEach((col) => {
                if (!col.valid) {
                    valid = false;
                }
            });
        });

        return valid;
    }

    return false;
});

/**
 * Initial import step - upload CSV file to server
 */
const startImport = (): void => {
    showUploadForm.value = true;
    importModal.value?.show();
};

/**
 * Show the loader and trigger the validation process
 */
const validateImport = (): void => {
    console.log("validate");

    showUploadForm.value = false;
    loadingMessage.value = "Validating File";
    isLoading.value = true;
};

/**
 * Get the results of the validation
 */
const getValidationResults = (): void => {
    console.log("get results");

    dataGet(
        route("cust-workbook.import.show", [wbHash.value, props.tableIndex]),
    ).then(
        (
            res: void | AxiosResponse<
                workbookValidationData[],
                workbookValidationData[]
            >,
        ) => {
            console.log(res);
            if (res) {
                validatedResults.value = res.data;
                isLoading.value = false;
                showValidationResults.value = true;
            }
        },
    );
};

/**
 * Trigger the import process
 */
const importData = (): void => {
    console.log("import data");

    loadingMessage.value = "Importing Data - Please Wait";
    showValidationResults.value = false;
    isLoading.value = true;

    dataPut(
        route("cust-workbook.import.update", [wbHash.value, props.tableIndex]),
        {},
    ).then((res) => console.log(res));
};

/**
 * Cancel the import and validation process
 */
const cancelImport = (): void => {
    console.log("cancel Import");
};

/**
 * Reset import data to be used again
 */
const cleanUp = () => {
    isLoading.value = false;
    showUploadForm.value = false;
    validatedResults.value = undefined;
};

onMounted(() => {
    Echo.channel(`workbook-import.${wbHash.value}${props.tableIndex}`)
        .listen(
            ".WorkbookTableImportValidationEvent",
            (event: { tableIndex: string }) => {
                console.log(event);
                if (event.tableIndex) {
                    getValidationResults();
                }
            },
        )
        .listenToAll((event) => console.log(event));
});
</script>

<template>
    <div>
        <BaseButton
            class="mx-2"
            text="Import Data"
            size="small"
            variant="info"
            pill
            @click="startImport"
        />
        <Modal
            ref="import-data-modal"
            title="Import Table Data"
            @hidden="cleanUp"
        >
            <AtomLoader v-if="isLoading" :text="loadingMessage" />
            <ImportTableDataForm
                v-if="showUploadForm"
                :table-index="tableIndex"
                @success="validateImport"
            />
            <div v-if="validatedResults && showValidationResults">
                <ValidationResults :validated-results="validatedResults" />
                <div v-if="fileIsValid" class="flex gap-1 my-2 justify-center">
                    <BaseButton
                        text="Append Current Data"
                        @click="importData"
                    />
                </div>
                <!-- <div class="flex justify-center my-2">
                    <BaseButton
                        text="Cancel"
                        variant="danger"
                        @click="cancelImport"
                    />
                </div> -->
            </div>
        </Modal>
    </div>
</template>
