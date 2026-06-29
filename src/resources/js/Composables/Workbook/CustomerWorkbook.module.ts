import errorModal from "@/Modules/errorModal";
import { computed, ref } from "vue";
import { dataPut } from "../axiosWrapper.module";

const bodyCopy = ref<workbookPage[]>();
export const isPreviewMode = ref<boolean>(false);
export const activePage = ref<string>("0");
export const wbHash = ref<string>();
export const hasError = ref<boolean>(false);
export const isPagePublic = computed<boolean>(() => {
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
export const initWorkbook = (
    workbook: customerWorkbook,
    isPublic?: boolean,
): void => {
    let wbType: "public_workbook" | "parsed_workbook" = isPublic
        ? "public_workbook"
        : "parsed_workbook";

    if (workbook[wbType]) {
        activePage.value = workbook[wbType].body[0].page;
        bodyCopy.value = workbook[wbType].body;
        wbHash.value = workbook.wb_hash;
    }
};

/**
 * Save a single input value in to the database
 */
export const saveWorkbookValue = (saveData: workbookSaveData): void => {
    if (isPreviewMode.value) {
        return;
    }

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
};
