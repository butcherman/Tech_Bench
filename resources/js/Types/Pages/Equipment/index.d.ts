type categoryList = {
    cat_id        : number;
    name          : string;
    equipment_type: equipType[];
}

type equipType = {
    cat_id  : number;
    equip_id: number;
    name    : string;
}

type equipWithDataType = {
    equipment_category: {
        cat_id: number;
        name  : string;
    }
    // data_field_type: dataListType[];
} & equipType;

type dataListType = {
    type_id: number;
    name   : string;
    hidden : boolean;
    in_use : boolean;
}
