import { v4 } from "uuid";

export const specialElements: workbookElement[] = [
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
