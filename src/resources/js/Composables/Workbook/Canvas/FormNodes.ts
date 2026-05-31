import { v4 } from "uuid";

export const formNodes: workbookNode[] = [
    {
        index: v4(),
        type: "input",
        component: "TextInput",
        props: {
            label: "",
            placeholder: "",
            help: "",
        },
        nodeLabel: {
            label: "Text Input",
            help: "One line input for text data",
            buttonIcon: "font",
        },
        nodeHelper: {
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
    },
    {
        index: v4(),
        type: "input",
        component: "TextAreaInput",
        props: {
            label: "",
            placeholder: "",
            help: "",
            rows: 5,
        },
        nodeLabel: {
            label: "Text Area Input",
            help: "Multi line input for text data",
            buttonIcon: "font",
        },
        nodeHelper: {
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
    },
    {
        index: v4(),
        type: "input",
        component: "DatePicker",
        props: {
            label: "",
            placeholder: "",
            help: "",
        },
        nodeLabel: {
            label: "Date Picker Input",
            help: "Input for selecting a date",
            buttonIcon: "calendar-days",
        },
        nodeHelper: {
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
    },
    {
        index: v4(),
        type: "input",
        component: "PhoneNumberInput",
        props: {
            label: "",
            placeholder: "",
            help: "",
        },
        nodeLabel: {
            label: "Phone Number Input",
            help: "Formatted Input for Phone Number",
            buttonIcon: "phone",
        },
        nodeHelper: {
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
    },
    {
        index: v4(),
        type: "input",
        component: "SelectInput",
        props: {
            label: "",
            placeholder: "",
            list: ["option 1", "option 2"],
            help: "",
        },
        nodeLabel: {
            label: "Select Input",
            help: "Drop Down Input",
            buttonIcon: "caret-down",
        },
        nodeHelper: {
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
    },
    {
        index: v4(),
        type: "input",
        component: "RadioGroupInput",
        props: {
            list: ["button 1", "button 2"],
            help: "",
        },
        nodeLabel: {
            label: "Radio Button Group",
            help: "Multiple Choice Input",
            buttonIcon: "circle-dot",
        },
        nodeHelper: {
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
    },
    {
        index: v4(),
        type: "input",
        component: "RangeInput",
        props: {
            label: "Range Input",
            valueText: "",
            min: 0,
            max: 100,
            help: "",
        },
        nodeLabel: {
            label: "Range Input",
            help: "Slider to Pick A Number",
            buttonIcon: "sliders",
        },
        nodeHelper: {
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
    },
    {
        index: v4(),
        type: "input",
        component: "SwitchInput",
        props: {
            label: "",
            center: false,
            help: "",
        },
        nodeLabel: {
            label: "Switch Input",
            help: "On/Off Toggle Switch",
            buttonIcon: "toggle-on",
        },
        nodeHelper: {
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
    },
];
