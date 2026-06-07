import errorModal from "@/Modules/errorModal";
import { computed, ref } from "vue";
import { dataPut } from "../axiosWrapper.module";

export const isPreviewMode = ref<boolean>(false);
export const activePage = ref<string>("0");
export const wbHash = ref<string>();
export const hasError = ref<boolean>(false);

const bodyCopy = ref<workbookPage[]>();
const isPagePublic = computed(() => {
    let currentPage = bodyCopy.value?.find(
        (page) => page.page === activePage.value,
    );

    if (currentPage?.canPublish) {
        return currentPage.canPublish;
    }

    return false;
});

/**
 * Set the necessary data for the workbook state
 */
export const initWorkbook = (workbook: customerWorkbook): void => {
    activePage.value = workbook.wb_skeleton.body[0].page;
    bodyCopy.value = workbook.wb_skeleton.body;
    wbHash.value = workbook.wb_hash;
};

/**
 * Save a single input value in to the database
 */
export const saveWorkbookValue = (event: InputEvent, index: string): void => {
    if (isPreviewMode.value) {
        return;
    }

    if (event.target) {
        let target = event.target as HTMLInputElement;

        console.log(index, target.value);

        let saveData = {
            index,
            value: target.value,
            public: isPagePublic.value,
            isTable: false,
        };

        dataPut(
            route("cust-workbook.save-value", [wbHash.value]),
            saveData,
            false,
        ).catch((err) => {
            hasError.value = true;

            let message =
                "Unable to save data.  Please refresh page and try again.";
            errorModal(err.status, message);
        });
    }
};

/**
 * Save a single table call in to the database
 */
export const saveTableCell = (
    event: InputEvent,
    tableIndex: string,
    rowIndex: number,
    columnName: string,
): void => {
    if (isPreviewMode.value) {
        return;
    }

    if (event.target) {
        let target = event.target as HTMLInputElement;

        let saveData = {
            table_index: tableIndex,
            row_index: rowIndex,
            column_name: columnName,
            value: target.value,
            public: isPagePublic.value,
            isTable: true,
        };

        dataPut(
            route("cust-workbook.save-value", [wbHash.value]),
            saveData,
            false,
        ).catch((err) => {
            hasError.value = true;
            console.log(err);

            let message =
                "Unable to save data.  Please refresh page and try again.";
            errorModal(err.status, message);
        });
    }
};
