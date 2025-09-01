import { v4 } from "uuid";

export const inputElements: workbookElement[] = [
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
    {
        index: v4(),
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
        index: v4(),
        type: "input",
        tag: "input",
        component: "RangeInput",
        props: {
            label: "",
            valueText: "",
            min: 0,
            max: 100,
            help: "",
        },
        assist: {
            label: {
                label: "Label",
                help: "Label for Input Text",
                type: "string",
            },
            valueText: {
                label: "Value Text",
                help: "Text to show next to the selected value",
                type: "string",
            },
            min: {
                label: "Minimum Value",
                help: "Lowest Number in the Range",
                type: "number",
            },
            max: {
                label: "Maximum Value",
                help: "Highest Number in the Range",
                type: "number",
            },
            help: {
                label: "Help Text",
                help: "Helpful text to show when Input is active",
                type: "string",
            },
        },
        componentData: {
            label: "Range Input",
            help: "Slider to Pick A Number",
            buttonIcon: "sliders",
        },
    },

    {
        index: v4(),
        type: "input",
        tag: "input",
        component: "SwitchInput",
        props: {
            label: "",
            center: false,
            help: "",
        },
        assist: {
            label: {
                label: "Label",
                help: "Label for Input Text",
                type: "string",
            },
            center: {
                label: "Center",
                help: "Center this switch",
                type: "boolean",
            },
            help: {
                label: "Help Text",
                help: "Helpful text to show when Input is active",
                type: "string",
            },
        },
        componentData: {
            label: "Switch Input",
            help: "On/Off Toggle Switch",
            buttonIcon: "toggle-on",
        },
    },
];
