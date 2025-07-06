import { computed } from "vue";
import { equipmentType } from "./WorkbookEditor";

export const elementList = computed<workbookElement[]>(() => [
    {
        index: "0",
        type: "text",
        tag: "h1",
        text: "Header 1",
        class: "text-center",
        componentData: {
            label: "Header",
            help: "Header text for Workbook",
            buttonText: "H1",
        },
    },
    {
        index: "1",
        type: "text",
        tag: "h3",
        text: "Sub-Header",
        class: "text-center",
        componentData: {
            label: "Sub Header",
            help: "Sub-Header text for Workbook",
            buttonText: "H3",
        },
    },
    {
        index: "2",
        type: "text",
        tag: "p",
        text: "Text Box",
        class: "text-center",
        componentData: {
            label: "Text Box",
            help: "Text Box for paragraph style text",
            buttonIcon: "paragraph",
        },
    },
    {
        index: "3",
        type: "static",
        tag: "h3",
        text: "[ Customer Name ]",
        class: "text-center",
        componentData: {
            label: "Customer Name",
            help: "Name of the Customer this WB is for",
            buttonText: "CN",
        },
    },
    {
        index: "4",
        type: "static",
        tag: "h3",
        text: equipmentType.value?.name ?? "[ Equipment Name ]",
        class: "text-center",
        componentData: {
            label: "Equipment Name",
            help: "WB Equipment Name",
            buttonText: "EQ",
        },
    },
]);
