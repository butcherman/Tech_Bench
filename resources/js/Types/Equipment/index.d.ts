type categoryList = {
    cat_id        : number;
    name          : string;
    equipment_type: equipment[];
}

type equipment = {
    cat_id  : number;
    equip_id: number;
    name    : string;
}

type equipWithData = {
    equipment_category: {
        cat_id: number;
        name  : string;
    }
    data_field_type: dataList[];
} & equipment;

type dataList = {
    type_id: number;
    name   : string;
    hidden : boolean;
    in_use : boolean;
}

// type equipSelectBox = {
//     [key: string]: categoryList;
// }