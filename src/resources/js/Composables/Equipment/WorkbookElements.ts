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
        index: "7",
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
    {
        index: "8",
        type: "input",
        tag: "input",
        component: "TextInput",
        props: {
            label: "",
            placeholder: "",
            help: "",
        },
        assist: {
            label: {
                label: "Label",
                help: "Label for Input",
                type: "string",
            },
            placeholder: {
                label: "Placeholder",
                help: "Placeholder text for empty Input",
                type: "string",
            },
            help: {
                label: "Help Text",
                help: "Helpful text to show when Input is active",
                type: "string",
            },
        },
        componentData: {
            label: "Text Input",
            help: "One line input for text data",
            buttonIcon: "font",
        },
    },
    {
        index: "9",
        type: "input",
        tag: "input",
        component: "TextAreaInput",
        props: {
            label: "",
            placeholder: "",
            help: "",
            rows: 5,
        },
        assist: {
            label: {
                label: "Label",
                help: "Label for Input Text",
                type: "string",
            },
            placeholder: {
                label: "Placeholder",
                help: "Placeholder text for empty Input",
                type: "string",
            },
            help: {
                label: "Help Text",
                help: "Helpful text to show when Input is active",
                type: "string",
            },
            rows: {
                label: "Rows",
                help: "Height of Input",
                type: "number",
            },
        },
        componentData: {
            label: "Text Area Input",
            help: "Multi line input for text data",
            buttonIcon: "font",
        },
    },
    {
        index: "10",
        type: "input",
        tag: "input",
        component: "DatePicker",
        props: {
            label: "",
            placeholder: "",
            help: "",
        },
        assist: {
            label: {
                label: "Label",
                help: "Label for Input Text",
                type: "string",
            },
            placeholder: {
                label: "Placeholder",
                help: "Placeholder text for empty Input",
                type: "string",
            },
            help: {
                label: "Help Text",
                help: "Helpful text to show when Input is active",
                type: "string",
            },
        },
        componentData: {
            label: "Date Picker Input",
            help: "Input for selecting a date",
            buttonIcon: "calendar-days",
        },
    },
    {
        index: "11",
        type: "input",
        tag: "input",
        component: "RadioGroupInput",
        props: {
            list: ["button 1", "button 2"],
            help: "",
        },
        assist: {
            list: {
                label: "List of Options",
                help: "Comma separated list of available button options",
                type: "array",
            },
            help: {
                label: "Help Text",
                help: "Helpful text to show when Input is active",
                type: "string",
            },
        },
        componentData: {
            label: "Radio Button Group",
            help: "Multiple Choice Input",
            buttonIcon: "circle-dot",
        },
    },
    {
        index: "12",
        type: "input",
        tag: "input",
        component: "PhoneNumberInput",
        props: {
            label: "",
            placeholder: "",
            help: "",
        },
        assist: {
            label: {
                label: "Label",
                help: "Label for Input Text",
                type: "string",
            },
            placeholder: {
                label: "Placeholder",
                help: "Placeholder text for empty Input",
                type: "string",
            },
            help: {
                label: "Help Text",
                help: "Helpful text to show when Input is active",
                type: "string",
            },
        },
        componentData: {
            label: "Phone Number Input",
            help: "Formatted Input for Phone Number",
            buttonIcon: "phone",
        },
    },
]);
