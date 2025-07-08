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
    {
        index: "5",
        type: "grid-wrapper",
        tag: "div",
        class: "grid grid-cols-2 gap-2 my-2 min-h-20",
        componentData: {
            label: "Two Column Grid",
            help: "Grid Row with two columns",
            buttonIcon: "table-columns",
        },
        container: [
            {
                index: "50",
                type: "wrapper",
                tag: "div",
                class: "min-h-20",
                container: [],
            },
            {
                index: "51",
                type: "wrapper",
                tag: "div",
                class: "min-h-20",
                container: [],
            },
        ],
    },
    {
        index: "6",
        type: "grid-wrapper",
        tag: "div",
        class: "grid grid-cols-3 gap-2 my-2 min-h-20",
        componentData: {
            label: "Three Column Grid",
            help: "Grid Row with three columns",
            buttonIcon: "table-columns",
        },
        container: [
            {
                index: "60",
                type: "wrapper",
                tag: "div",
                class: "min-h-20",
                container: [],
            },
            {
                index: "61",
                type: "wrapper",
                tag: "div",
                class: "min-h-20",
                container: [],
            },
            {
                index: "62",
                type: "wrapper",
                tag: "div",
                class: "min-h-20",
                container: [],
            },
        ],
    },
    {
        index: "5",
        type: "wrapper",
        tag: "fieldset",
        class: "border border-slate-300 min-h-20 rounded-lg p-2",
        text: "",
        componentData: {
            label: "Fieldset",
            help: "Bordered Container for Form Elements",
            buttonIcon: "rectangle-list",
        },
        container: [],
    },
]);
