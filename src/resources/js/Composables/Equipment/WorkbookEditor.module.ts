import { ref, unref } from "vue";
import { v4 } from "uuid";
import okModal from "@/Modules/okModal";
import { dataPost } from "../axiosWrapper.module";

export const equipmentType = ref<equipment>();
export const workbookData = ref<workbookWrapper>();
const cleanWorkbook = ref<workbookWrapper>();

export const initWorkbook = (
    equipType: equipment,
    workbook: workbookWrapper
): void => {
    equipmentType.value = equipType;
    workbookData.value = copyWorkbook(workbook);
    cleanWorkbook.value = copyWorkbook(workbook);
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
 * Save the WB to database
 */
export const saveWorkbook = () => {
    console.log("save workbook");

    if (
        workbookData.value?.body.length == 1 &&
        !workbookData.value.body[0].container.length
    ) {
        okModal(
            "Cannot Save an Empty Workbook.  Please build Workbook Data First"
        );
        return;
    }

    // dataPost(route("workbooks.store", equipmentType.value?.equip_id), {
    //     workbook_data: unref(workbookData),
    // }).then((res) => {
    //     console.log(res);
    //     if (res && res.data.success) {
    //         isDirty.value = false;
    //         updateSavedWorkbook();
    //         updatePreview();
    //         appStore.pushFlashMsg({
    //             id: "new",
    //             type: "success",
    //             message: "Workbook Saved",
    //         });
    //     }
    // });
};

/**
 * Undo all changes since last save
 */
export const resetWorkbook = () => {
    console.log("reset workbook");
    workbookData.value = copyWorkbook(cleanWorkbook.value);
};

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
