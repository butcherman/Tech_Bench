type categoryList = {
    cat_id: number;
    name: string;
    equipment_type: equipment[];
};

type equipment = {
    cat_id: number;
    equip_id: number;
    name: string;
    data_field_type?: dataTypes[];
};

type dataTypes = {
    type_id: number;
    name: string;
    pattern: string | null;
    masked: boolean;
    is_hyperlink: boolean;
    allow_copy: boolean;
    in_use: boolean;
};
