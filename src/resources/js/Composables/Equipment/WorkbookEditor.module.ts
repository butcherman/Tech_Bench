import { ref } from "vue";
import { v4 } from "uuid";

/**
 * Equipment type this WB is for
 */
export const equipmentType = ref<equipment>();

/**
 * State of WB since last save
 */
export const isDirty = ref<boolean>(false);
export const imDirty = () => {
    isDirty.value = true;
};

/*
|-------------------------------------------------------------------------------
| Workbook Components
|-------------------------------------------------------------------------------
*/

/**
 * Make duiplcate copy of element with new ID
 */
export const cloneElement = (element: workbookElement): workbookEntry => {
    // Make deep copy of element
    let newElement = JSON.parse(JSON.stringify(element));
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
