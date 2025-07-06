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
    console.log(workbook, equipment);
    equipmentType.value = equipment;
    workbookData.value = { ...workbook };
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
