import { reactive, ref, unref, watch } from "vue";
import { v4 } from "uuid";
import { dataPut } from "../axiosWrapper.module";
import { Container } from "postcss";

export const equipmentType = ref<equipment>();
export const activePage = ref<string>("0");
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
    activePage.value = workbookData.body[0].page;
};

export const updateCleanWorkbook = () => {
    cleanWorkbook.value = copyWorkbook(workbookData);
};

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

    workbookData.body.push(newPage);
    activePage.value = newPage.page;

    imDirty();
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
export const showWbEditor = ref<boolean>(false);
export const editingComponent = ref<workbookEntry | workbookPage>();

export const closeWbEditor = () => {
    showWbEditor.value = false;
    imDirty();
};

export const onWbEditorClose = (): void => {
    editingComponent.value = undefined;
};

/**
 * Edit the meta data in the selected component
 */
export const editComponent = (
    component: workbookElement | workbookPage
): void => {
    editingComponent.value = component;
    showWbEditor.value = true;
};

/**
 * Create a copy of a component and place directly below selected component.
 */
export const cloneComponent = (
    component: workbookElement,
    container: workbookElement[]
): void => {
    let newComponent = cloneElement(component);
    let index = container.indexOf(component);

    container.splice(index, 0, newComponent);
};

/**
 * Delete a component from the canvas.
 */
export const deleteComponent = (
    component: workbookElement,
    container: workbookElement[]
): void => {
    let index = container.indexOf(component);
    container.splice(index, 1);

    imDirty();
};

/**
 * Delete a page from the canvas.
 */
export const deletePage = (page: workbookPage) => {
    let index = workbookData.body.indexOf(page);

    workbookData.body.splice(index, 1);

    if (page.page === activePage.value) {
        activePage.value = workbookData.body[0].page;
    }
};
