import { v4 } from "uuid";

export const designNodes: workbookNode[] = [
    {
        index: v4(),
        type: "grid-wrapper",
        props: {
            tag: "div",
            class: "grid grid-cols-2 gap-2 my-2 min-h-20",
        },
        nodeLabel: {
            label: "Two Column Grid",
            help: "Grid Row with two columns",
            buttonIcon: "table-columns",
        },
        contents: [
            {
                index: v4(),
                type: "wrapper",
                contents: [],
                props: {
                    tag: "div",
                    class: "min-h-20",
                },
            },
            {
                index: v4(),
                type: "wrapper",
                contents: [],
                props: {
                    tag: "div",
                    class: "min-h-20",
                },
            },
        ],
    },
    {
        index: v4(),
        type: "grid-wrapper",
        props: {
            tag: "div",
            class: "grid grid-cols-3 gap-2 my-2 min-h-20",
        },
        nodeLabel: {
            label: "Three Column Grid",
            help: "Grid Row with three columns",
            buttonIcon: "table-columns",
        },
        contents: [
            {
                index: v4(),
                type: "wrapper",
                props: {
                    tag: "div",
                    class: "min-h-20",
                },
                contents: [],
            },
            {
                index: v4(),
                type: "wrapper",
                props: {
                    tag: "div",
                    class: "min-h-20",
                },
                contents: [],
            },
            {
                index: v4(),
                type: "wrapper",
                props: {
                    tag: "div",
                    class: "min-h-20",
                },
                contents: [],
            },
        ],
    },
    {
        index: v4(),
        type: "fieldset",
        props: {
            tag: "fieldset",
            class: "border border-slate-300 min-h-20 rounded-lg p-2",
            text: "",
        },
        nodeLabel: {
            label: "Fieldset",
            help: "Bordered Container for Form Elements",
            buttonIcon: "rectangle-list",
        },
        contents: [],
    },
    {
        index: v4(),
        type: "static",
        props: {
            tag: "h3",
            text: "[ Customer Name ]",
            class: "text-center",
        },
        nodeLabel: {
            label: "Customer Name",
            help: "Name of the Customer this WB is for",
            buttonText: "CN",
        },
    },
    {
        index: v4(),
        type: "static",
        props: {
            tag: "h3",
            text: "[ Equipment Name ]",
            class: "text-center",
        },
        nodeLabel: {
            label: "Equipment Name",
            help: "WB Equipment Name",
            buttonText: "EQ",
        },
    },
    {
        index: v4(),
        type: "text",
        props: {
            tag: "h1",
            text: "Header 1",
            class: "text-center",
        },
        nodeLabel: {
            label: "Header",
            help: "Header text for Workbook",
            buttonText: "H1",
        },
    },
    {
        index: v4(),
        type: "text",
        props: {
            tag: "h2",
            text: "Sub-Header",
            class: "text-center",
        },
        nodeLabel: {
            label: "Sub Header",
            help: "Sub-Header text for Workbook",
            buttonText: "H2",
        },
    },
    {
        index: v4(),
        type: "text",
        props: {
            tag: "div",
            text: "Text Box",
            class: "",
        },
        nodeLabel: {
            label: "Text Box",
            help: "Text Box for paragraph style text",
            buttonIcon: "paragraph",
        },
    },
];
