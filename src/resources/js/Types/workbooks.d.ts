type workbookWrapper = {
    header: workbookNode[];
    body: workbookPage[];
    footer: workbookNode[];
};

type workbookPage = {
    page: string;
    title: string;
    canPublish: boolean;
    contents: workbookNode[];
};

type workbookNode = {
    index: string;
    type:
        | "static"
        | "grid-wrapper"
        | "wrapper"
        | "fieldset"
        | "text"
        | "input"
        | "data-table";
    props: {
        tag?: string;
        class?: string;
        cols?: number;
        text?: string;
        label?: string;
        placeholder?: string;
        help?: string;
        rows?: number;
        list?: string[];
        valueText?: string;
        min?: number;
        max?: number;
        center?: boolean;
        defaultRows?: number;
        hideBorders?: boolean;
        allowAddRow?: boolean;
        allowDeleteRow?: boolean;
        allowImport?: boolean;
        allowExport?: boolean;
        numberRows?: boolean;
        columns?: workbookTableColumn[];
    };
    contents?: workbookNode[];
    component?: string;
    nodeLabel?: {
        label: string;
        help: string;
        buttonIcon?: string;
        buttonText?: string;
    };
    nodeHelper?: {
        [key: string]: {
            label: string;
            help: string;
            type: string;
        };
    };
};

type workbookTableColumnTypes = "Text" | "Number" | "Checkbox" | "Drop List";

type workbookTableColumn = {
    name: string;
    type: workbookTableColumnTypes;
    list: string;
};

type workbookDropEvent = {
    added?: {
        element: workbookNode;
        newIndex: number;
    };
    moved?: {
        element: workbookNode;
        newIndex: number;
        oldIndex: number;
    };
};
