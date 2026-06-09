import errorModal from "@/Modules/errorModal";
import { computed, ref } from "vue";
import { dataPut } from "../axiosWrapper.module";

export const isPreviewMode = ref<boolean>(false);
export const activePage = ref<string>("0");
export const wbHash = ref<string>();
export const hasError = ref<boolean>(false);

const bodyCopy = ref<workbookPage[]>();
export const isPagePublic = computed(() => {
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
    activePage.value = workbook.parsed_workbook.body[0].page;
    bodyCopy.value = workbook.parsed_workbook.body;
    wbHash.value = workbook.wb_hash;
};

/**
 * Save a single input value in to the database
 */
export const saveWorkbookValue = (saveData: workbookSaveData): void => {
    if (isPreviewMode.value) {
        return;
    }

    console.log(saveData);

    dataPut(
        route("cust-workbook.save-value", [wbHash.value]),
        saveData,
        false,
    ).catch((err) => {
        console.log(err);
        hasError.value = true;

        let message =
            "Unable to save data.  Please refresh page and try again.";
        errorModal(err.status, message);
    });
};
