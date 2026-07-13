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
                },
                {
                    name: "Col 2",
                    type: "string",
                },
                {
                    name: "Col 3",
                    type: "string",
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
];
