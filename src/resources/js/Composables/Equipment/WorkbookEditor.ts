import { v4 } from "uuid";
import { ref } from "vue";

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

export const setWorkbookData = (
    workbook: workbookWrapper,
    equipment: equipment
): void => {
    equipmentType.value = equipment;
    workbookData.value = { ...workbook };
    activePage.value = workbookData.value.body[0].page;
};

/*
|-------------------------------------------------------------------------------
| Workbook Pages
|-------------------------------------------------------------------------------
*/
export const activePage = ref("0");

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
};
