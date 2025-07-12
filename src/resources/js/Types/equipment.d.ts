type equipmentCategory = {
    cat_id: number;
    name: string;
    equipment_type: equipment[];
};

type equipment = {
    cat_id: number;
    equip_id: number;
    name: string;
    allow_public_tip: boolean;
    data_field_type?: dataTypes[];
};

type dataTypes = {
    type_id: number;
    name: string;
    pattern: string | null;
    pattern_error: string | null;
    masked: boolean;
    is_hyperlink: boolean;
    allow_copy: boolean;
    do_not_log_value: boolean;
    in_use: boolean;
};

/*
|-------------------------------------------------------------------------------
| Onboarding Workbook Types
|-------------------------------------------------------------------------------
*/

type workbookWrapper = {
    header: workbookEntry[];
    body: workbookPage[];
    footer: workbookEntry[];
};

type workbookPage = {
    page: string;
    title: string;
    canPublish: boolean;
    container: workbookEntry[];
};

type workbookElement = {
    componentData?: {
        label: string;
        help: string;
        buttonText?: string;
        buttonIcon?: string;
    };
} & workbookEntry;

type workbookEntry = {
    index: string;
    type: "text" | "static" | "input" | "wrapper" | "grid-wrapper";
    tag: string;
    component?: string;
    props?: { [key: string]: string | string[] | number | boolean };
    assist?: {
        [key: string]: {
            label: string;
            help: string;
            type: "string" | "number" | "boolean" | "array";
        };
    };
    text?: string;
    class?: string;
    container?: workbookEntry[];
};

type workbookDropEvent = {
    added?: {
        element: workbookEntry;
        newIndex: number;
    };
    moved?: {
        element: workbookEntry;
        newIndex: number;
        oldIndex: number;
    };
};
