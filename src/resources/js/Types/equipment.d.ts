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
    header: any[];
    body: any[];
    footer: any[];
};

type workbookPage = {
    page: string;
    title: string;
    canPublish: boolean;
    container: any[];
};

type workbookEntry = {
    index: string;
    props: {
        [key: string]: string | boolean;
    };
    readonly html: string;
};

type workbookElement = {
    label: string;
    help: string;
    buttonText?: string;
    buttonIcon?: string;
} & workbookEntry;
