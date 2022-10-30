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
