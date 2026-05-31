type workbookWrapper = {
    header: workbookNode[];
    body: workbookPage[];
    footer: workbookNode[];
};

type workbookPage = {
    page: string;
    title: string;
    canPublish: boolean;
    contents: any[];
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
        | "table";
    props: {
        tag?: string;
        class?: string;
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
        columns?: {
            name: string;
            type: string;
        }[];
        // [key: string]: string;
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

type workbookDropEvent = {
    // added?: {
    //     element: workbookEntry;
    //     newIndex: number;
    // };
    // moved?: {
    //     element: workbookEntry;
    //     newIndex: number;
    //     oldIndex: number;
    // };
};
