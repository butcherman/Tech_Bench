import { v4 } from "uuid";

export const textInputElements: workbookElement[] = [
    {
        index: v4(),
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
        index: v4(),
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
        index: v4(),
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
        index: v4(),
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
    {
        index: v4(),
        type: "input",
        tag: "input",
        component: "SelectInput",
        props: {
            label: "",
            placeholder: "",
            list: ["option 1", "option 2"],
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
            label: "Select Input",
            help: "Drop Down Input",
            buttonIcon: "caret-down",
        },
    },
];
