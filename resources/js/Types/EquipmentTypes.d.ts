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

export type customerEquipmentType = {
    cust_equip_id: number;
    shared       : boolean;
    customer_equipment_data: customerEquipmentDataType[];
} & equipType;

export interface customerEquipmentDataType {
    [key:string]: string | boolean;
}

export interface dataListType {
    type_id: number;
    name   : string;
    hidden : boolean;
    in_use : boolean;
}