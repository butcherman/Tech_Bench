import { v4 } from "uuid";

export const tableNodes: workbookNode[] = [
    {
        index: v4(),
        type: "table",
        props: {
            columns: [
                {
                    name: "Col 1",
                    type: "text",
                },
                {
                    name: "Col 2",
                    type: "text",
                },
                {
                    name: "Col 3",
                    type: "text",
                },
            ],
            defaultRows: 10,
            hideBorders: false,
            allowAddRow: true,
            allowDeleteRow: true,
            allowImport: true,
            allowExport: true,
        },
        nodeLabel: {
            label: "Data Table",
            help: "Table for mass import/export of data",
            buttonIcon: "table-cells",
        },
    },
];
