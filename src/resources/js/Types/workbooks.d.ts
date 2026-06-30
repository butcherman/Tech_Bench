type customerWorkbook = {
    wb_id: number;
    wb_hash: string;
    cust_id: number;
    cust_equip_id: number;
    wb_skeleton: workbookWrapper;
    wb_version: string;
    publish_until: string;
    publish_until_raw: string;
    created_at: string;
    updated_at: string;
    published: boolean;
    parsed_workbook: workbookWrapper;
    public_workbook?: workbookWrapper;
    up_to_date?: boolean;
};

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
        | "data-table"
        | "header";
    props: workbookNodeProps;
    contents?: workbookNode[];
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

type workbookNodeProps = {
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
    component?: string;
    [key: string]: any;
};

type workbookTableColumnType = "string" | "integer" | "boolean" | "enum";

type workbookTableColumn = {
    name: string;
    type: workbookTableColumnType;
    allowDefault: boolean;
    defaultValue: string;
    hiddenColumn: boolean;
    list?: string[] | string;
};

type workbookTableRow = {
    index: string;
    [key: string]: any;
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

type workbookValueEvent = {
    model: workbookValue;
};

type workbookTableValueEvent = {
    model: workbookTableValue;
};

type workbookValue = {
    index: string;
    value: string;
    public: boolean;
};

type workbookTableValue = {
    table_index: string;
    row_index: string | number;
    column_name: string;
    public: boolean;
    value: string;
};

type workbookSaveData =
    | ({
          isTable: boolean;
      } & workbookValue)
    | workbookTableValue;

type workbookValidationData = {
    [key: string]: {
        valid: boolean;
        data_type: string;
        value: any;
        validation_error: string | null;
    };
};
