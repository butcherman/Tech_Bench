import { v4 } from "uuid";
import { ref, unref } from "vue";
import { dataPut } from "../axiosWrapper.module";

/*
|-------------------------------------------------------------------------------
| Workbook and Initial Workbook Data
|-------------------------------------------------------------------------------
*/
export const equipmentType = ref<equipment>();
export const workbookData = ref<workbookWrapper>({
    header: [],
    body: [],
    footer: [],
});
const savedWorkbook = ref<workbookWrapper>();

export const setWorkbookData = (
    workbook: workbookWrapper,
    equipment: equipment
): void => {
    equipmentType.value = equipment;
    workbookData.value = JSON.parse(JSON.stringify(workbook));
    activePage.value = workbookData.value.body[0].page;
    updateSavedWorkbook();
    updatePreview();
};

export const updateSavedWorkbook = (): void => {
    savedWorkbook.value = JSON.parse(JSON.stringify(workbookData.value));
};

export const resetWorkbookData = () => {
    workbookData.value = JSON.parse(JSON.stringify(savedWorkbook.value));
};

export const isDirty = ref<boolean>(false);
export const imDirty = () => {
    isDirty.value = true;
};

/**
 * Sent changes to page to update live preview
 */
export const updatePreview = () => {
    dataPut(route("workbooks.update", equipmentType.value?.equip_id), {
        workbook_data: unref(workbookData),
    });
};

/*
|-------------------------------------------------------------------------------
| Workbook Pages
|-------------------------------------------------------------------------------
*/
export const activePage = ref<string>("0");

/**
 * Create a new Blank Page
 */
export const addBlankPage = (): void => {
    let newPage = {
        page: v4(),
        title: "New Page",
        canPublish: true,
        container: [],
    };

    workbookData.value.body.push(newPage);
    activePage.value = newPage.page;

    imDirty();
    updatePreview();
};

/**
 * Edit the data for an existing page
 */
export const editingPage = ref<workbookPage>();
export const editPageData = (page: workbookPage): void => {
    editingPage.value = page;
    showEditor.value = true;
};

/**
 * Destroy a page and all data inside.
 */
export const deletePage = (page: workbookPage): void => {
    let pageIndex = workbookData.value.body.indexOf(page);

    workbookData.value.body.splice(pageIndex, 1);

    imDirty();
    updatePreview();
};

/*
|-------------------------------------------------------------------------------
| Editors for the different element types
|-------------------------------------------------------------------------------
*/
export const showEditor = ref<boolean>(false);
export const editingElement = ref<workbookEntry>();

export const editElement = (el: workbookEntry): void => {
    showEditor.value = true;
    editingElement.value = el;
};

export const closeEditor = () => {
    showEditor.value = false;
    editingElement.value = undefined;
    updatePreview();
};

export const deleteElement = (
    element: workbookElement,
    container: workbookElement[]
): void => {
    let index = container.indexOf(element);

    container.splice(index, 1);

    imDirty();
    updatePreview();
};
