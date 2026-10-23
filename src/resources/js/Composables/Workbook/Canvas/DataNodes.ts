import { v4 } from "uuid";

export const dataNodes: workbookNode[] = [
    {
        index: v4(),
        type: "data-table",
        props: {
            columns: [
                {
                    name: "Col 1",
                    type: "string",
                    allowDefault: false,
                    defaultValue: "",
                    hiddenColumn: false,
                },
                {
                    name: "Col 2",
                    type: "string",
                    allowDefault: false,
                    defaultValue: "",
                    hiddenColumn: false,
                },
                {
                    name: "Col 3",
                    type: "string",
                    allowDefault: false,
                    defaultValue: "",
                    hiddenColumn: false,
                },
            ],
            allowAddRow: true,
            allowDeleteRow: true,
            allowExport: true,
            allowImport: true,
            defaultRows: 10,
            hideBorders: false,
            numberRows: false,
        },
        nodeLabel: {
            label: "Data Table",
            help: "Table for mass import/export of data",
            buttonIcon: "table-cells",
        },
    },
    {
        index: v4(),
        type: "task-list",
        props: {
            allowAddRow: false,
            allowDeleteRow: false,
            centerList: false,
            defaultList: ["item 1", "item 2", "item 3"],
            title: "Task List",
        },
        nodeLabel: {
            label: "Task List",
            help: "List of To-Do Items",
            buttonIcon: "clipboard-list",
        },
    },
];
