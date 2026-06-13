<script setup lang="ts">
import AtomLoader from "../_Base/Loaders/AtomLoader.vue";
import BaseButton from "../_Base/Buttons/BaseButton.vue";
import ImportTableDataForm from "@/Forms/Workbook/ImportTableDataForm.vue";
import Modal from "../_Base/Modal.vue";
import verifyModal from "@/Modules/verifyModal/index.js";
import { computed, onMounted, ref, useTemplateRef } from "vue";
import { v4 } from "uuid";
import { dataDelete } from "@/Composables/axiosWrapper.module.js";
import {
    isPagePublic,
    saveWorkbookValue,
    wbHash,
} from "@/Composables/Workbook/CustomerWorkbook.module.js";

const emit = defineEmits<{
    "push-row": [workbookTableRow];
}>();

const props = defineProps<{
    tableIndex: string;
}>();

const importModal = useTemplateRef("import-modal");

const isLoading = ref<boolean>(false);
const showImportForm = ref<boolean>(true);
const showValidator = ref<boolean>(false);
const fileValidated = ref<boolean>(false);
const validatedFile = ref<workbookValidationData[]>();
const loadingMessage = computed(() =>
    fileValidated.value ? "Importing File Data" : "Validating File Data",
);
const fileIsValid = computed(() => {
    if (validatedFile.value) {
        let valid = true;

        // Check if any of the table fields are invalid
        validatedFile.value.forEach((row) => {
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
 * When the file is uploaded, change to the loading screen while it is being validated
 */
const onSuccessfulFileUpload = () => {
    isLoading.value = true;
    showImportForm.value = false;
    showValidator.value = true;
};

/**
 * After Validation, show the results
 */
const onSuccessfulValidation = (event: workbookValidationEvent) => {
    isLoading.value = false;
    validatedFile.value = event.validationData;
    fileValidated.value = true;
};

/**
 * Import the validated data into the workbook values
 */
const importDataConfirmation = (append: boolean) => {
    if (!append && validatedFile.value) {
        verifyModal(
            "Are You Sure?",
            "All existing data will be replaced.  This cannot be undone",
        ).then((res) => {
            if (res) {
                console.log("replace data");

                // dataDelete(route('cust-workbook.wipe', [

                // ]))
            }
        });
    }

    if (append && validatedFile.value) {
        isLoading.value = true;
        importTableData(validatedFile.value).then(() =>
            importModal.value?.hide(),
        );
    }
};

/**
 * Import table data from CSV file
 */
const importTableData = async (tableData: workbookValidationData[]) => {
    // Build each row individually
    tableData.forEach((row) => {
        let newRow: workbookTableRow = {
            index: v4(),
        };

        // Build and save each individual column
        let columns = Object.entries(row);
        columns.forEach(([col, data]) => {
            let saveData: workbookSaveData = {
                table_index: props.tableIndex,
                row_index: newRow.index,
                column_name: col,
                public: isPagePublic.value,
                value: data.value,
                isTable: true,
            };

            newRow[col] = data.value;
            saveWorkbookValue(saveData);
        });

        // Add row to UI
        emit("push-row", newRow);
    });
};

onMounted(() => {
    /**
     * Register to listen for updated table imports
     */
    Echo.channel(`workbook-import.${wbHash.value}`).listen(
        ".WorkbookTableImportValidationEvent",
        (event: workbookValidationEvent) => {
            if (event.tableIndex === props.tableIndex) {
                onSuccessfulValidation(event);
            }
        },
    );
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
            @click="importModal?.show"
        />
        <Modal title="Import Table Data" ref="import-modal">
            <ImportTableDataForm
                v-if="showImportForm"
                :table-index="tableIndex"
                @success="onSuccessfulFileUpload"
            />
            <div v-if="showValidator">
                <AtomLoader v-if="isLoading" :text="loadingMessage" />
                <div v-if="validatedFile && fileValidated">
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th
                                    v-for="(col, index) in validatedFile[0]"
                                    :key="index"
                                    class="border border-slate-300"
                                    :class="{
                                        'bg-red-300':
                                            col.validation_error ===
                                            'Invalid Column',
                                    }"
                                >
                                    {{ index }}
                                    <div
                                        v-if="!col.valid"
                                        class="text-sm text-danger"
                                    >
                                        {{ col.validation_error }}
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(row, index) in validatedFile"
                                :key="index"
                            >
                                <td
                                    v-for="(col, name) in row"
                                    :key="name"
                                    :class="{ 'bg-red-300': !col.valid }"
                                    class="border border-slate-300 px-2"
                                >
                                    {{ col.value }}
                                    <div
                                        v-if="!col.valid"
                                        class="text-danger text-sm"
                                    >
                                        {{ col.validation_error }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="w-full my-2" v-if="fileValidated">
                    <h3 class="text-center text-danger" v-if="!fileIsValid">
                        Errors In File - Please Correct And Try Again
                    </h3>
                    <div v-else>
                        <BaseButton
                            class="mx-1"
                            text="Append Current Data"
                            @click="importDataConfirmation(true)"
                        />
                        <!-- <BaseButton
                            class="mx-1"
                            text="Replace Current Data"
                            @click="importDataConfirmation(false)"
                        /> -->
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>
