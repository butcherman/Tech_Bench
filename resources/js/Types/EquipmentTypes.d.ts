export interface categoryList {
    cat_id        : number;
    name          : string;
    equipment_type: equipType[];
}

export interface equipType {
    cat_id  : number;
    equip_id: number;
    name    : string;
}

export type equipWithDataType = {
    equipment_category: {
        cat_id: number;
        name  : string;
    }
    data_field_type: dataListType[];
} & equipType;

export interface dataListType {
    type_id: number;
    name   : string;
    hidden : boolean;
    in_use : boolean;
}
