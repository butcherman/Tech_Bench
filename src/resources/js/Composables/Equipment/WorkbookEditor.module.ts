import { reactive, ref, unref, watch } from "vue";
import { v4 } from "uuid";
import { dataPut } from "../axiosWrapper.module";

export const equipmentType = ref<equipment>();
const cleanWorkbook = ref<workbookWrapper>();
export const workbookData = reactive<workbookWrapper>({
    header: [],
    body: [],
    footer: [],
});

export const initWorkbook = (
    equipType: equipment,
    workbook: workbookWrapper
): void => {
    equipmentType.value = equipType;
    cleanWorkbook.value = copyWorkbook(workbook);
    workbookData.header = copyWorkbook(workbook.header);
    workbookData.body = copyWorkbook(workbook.body);
    workbookData.footer = copyWorkbook(workbook.footer);
};

export const updateCleanWorkbook = () => {
    cleanWorkbook.value = copyWorkbook(workbookData);
};

/*
|-------------------------------------------------------------------------------
| WB and WB Save state
|-------------------------------------------------------------------------------
*/

/**
 * State of WB since last save
 */
export const isDirty = ref<boolean>(false);
export const imDirty = () => {
    isDirty.value = true;
};

/**
 * Undo all changes since last save
 */
export const resetWorkbook = () => {
    console.log("reset workbook");
    if (cleanWorkbook.value) {
        workbookData.header = copyWorkbook(cleanWorkbook.value.header);
        workbookData.body = copyWorkbook(cleanWorkbook.value.body);
        workbookData.footer = copyWorkbook(cleanWorkbook.value.footer);

        isDirty.value = false;
    }
};

/**
 * Update the Dirty changes to show on the preview page
 */
watch(workbookData, (newWb) => {
    console.log("update preview", newWb);
    dataPut(route("workbooks.update", equipmentType.value?.equip_id), {
        workbook_data: unref(workbookData),
    });
});

/*
|-------------------------------------------------------------------------------
| Workbook Components
|-------------------------------------------------------------------------------
*/

/**
 * Make a deep copy duiplicate of the workbook.
 */
export const copyWorkbook = <T>(wbData: T): T => {
    return JSON.parse(JSON.stringify(wbData));
};

/**
 * Make duiplcate copy of element with new ID
 */
export const cloneElement = (element: workbookElement): workbookEntry => {
    // Make deep copy of element
    let newElement = copyWorkbook(element);
    delete newElement.componentData;

    newElement.index = v4();

    // If this element has children, create unique ID's on the child elements
    newElement.container?.forEach((elem: workbookEntry) => (elem.index = v4()));

    imDirty();

    return newElement;
};

/*
|-------------------------------------------------------------------------------
| Element Data Editor
|-------------------------------------------------------------------------------
*/
export const showWbEditor = ref(false);

export const onWbEditorClose = () => {
    console.log("editor closed");
};
