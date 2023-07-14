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

type equipWithReferences = {
    customer: customer[];
    tech_tip: techTip[];
} & equipment

type equipWithData = {
    equipment_category: {
        cat_id: number;
        name  : string;
    }
    data_field_type: dataTypes[];
} & equipment;

type dataTypes = {
    type_id: number;
    name   : string;
    pattern: string;
    masked : boolean;
    required: boolean;
    in_use : boolean;
}

// type equipSelectBox = {
//     [key: string]: categoryList;
// }