import { reactive, ref } from "vue";

/*
|-------------------------------------------------------------------------------
| Base Workbook Information & Workbook State
|-------------------------------------------------------------------------------
*/
const cleanWorkbook = ref<workbookWrapper>();
export const equipmentType = ref<equipment>();
export const activePage = ref<string>("0");
export const workbookData = reactive<workbookWrapper>({
    header: [],
    body: [],
    footer: [],
});

/**
 * Initialize the Workbook builder and set the initial data states
 */
export const initWorkbook = (
    equipType: equipment,
    workbook: workbookWrapper,
): void => {
    equipmentType.value = equipType;
    cleanWorkbook.value = copyWorkbook(workbook);
    workbookData.header = copyWorkbook(workbook.header);
    workbookData.body = copyWorkbook(workbook.body);
    workbookData.footer = copyWorkbook(workbook.footer);
    activePage.value = workbookData.body[0].page;
};

/**
 * Change workbook state for a successful save event
 */
export const onSuccessfulSave = () => {
    isDirty.value = false;
    cleanWorkbook.value = copyWorkbook(workbookData);
};

/**
 * Undo all changes since last save
 */
export const resetWorkbook = () => {
    if (cleanWorkbook.value) {
        workbookData.header = copyWorkbook(cleanWorkbook.value.header);
        workbookData.body = copyWorkbook(cleanWorkbook.value.body);
        workbookData.footer = copyWorkbook(cleanWorkbook.value.footer);

        isDirty.value = false;
    }
};

/**
 * Make a deep copy duiplicate of the workbook or Node.
 */
export const copyWorkbook = <T>(wbData: T): T => {
    return JSON.parse(JSON.stringify(wbData));
};

/**
 * State of WB since last save
 */
export const isDirty = ref<boolean>(false);
export const imDirty = (): void => {
    isDirty.value = true;
};

/*
|-------------------------------------------------------------------------------
| Node Data Editor
|-------------------------------------------------------------------------------
*/
export const activeNode = ref<workbookNode | workbookPage>();

/**
 * Delete a Node from the canvas.
 */
export const deleteNode = (
    element: workbookNode,
    container: workbookNode[],
): void => {
    let index = container.indexOf(element);
    container.splice(index, 1);

    imDirty();
};
