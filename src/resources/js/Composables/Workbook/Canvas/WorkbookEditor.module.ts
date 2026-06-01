import { v4 } from "uuid";
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
export const onSuccessfulSave = (): void => {
    isDirty.value = false;
    cleanWorkbook.value = copyWorkbook(workbookData);
};

/**
 * Undo all changes since last save
 */
export const resetWorkbook = (): void => {
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
| Workbook Pages
|-------------------------------------------------------------------------------
*/

/**
 * Create a new Blank Page
 */
export const addBlankPage = (): void => {
    let newPage: workbookPage = {
        page: v4(),
        title: "New Page",
        canPublish: true,
        contents: [],
    };

    workbookData.body.push(newPage);
    activePage.value = newPage.page;

    imDirty();
};

/**
 * Delete a page from the canvas.
 */
export const deletePage = (page: workbookPage): void => {
    console.log("delete page");
    let index = workbookData.body.indexOf(page);

    workbookData.body.splice(index, 1);
    imDirty();

    if (page.page === activePage.value) {
        activePage.value = workbookData.body[0].page;
    }
};

/*
|-------------------------------------------------------------------------------
| Node Data Editor
|-------------------------------------------------------------------------------
*/
export const showNodeEditor = ref<boolean>(false);
export const activeNode = ref<workbookNode | workbookPage>();

/**
 * Close the Editor Window
 */
export const closeNodeEditor = (): void => {
    showNodeEditor.value = false;
    imDirty();
};

/**
 * Make duiplcate copy of node with new ID
 */
export const getClonedNode = (node: workbookNode): workbookNode => {
    // Make deep copy of element
    let newNode = copyWorkbook(node);
    delete newNode.nodeLabel;

    newNode.index = v4();
    imDirty();

    // If this element has children, create unique ID's on the child elements
    newNode.contents?.forEach((nd: workbookNode) => (nd.index = v4()));

    return newNode;
};

/**
 * Edit data attached to a Node
 */
export const editNode = (node: workbookNode): void => {
    console.log("edit node");
    activeNode.value = node;
    showNodeEditor.value = true;
};

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
