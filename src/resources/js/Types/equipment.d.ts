type categoryList = {
    cat_id: number;
    name: string;
    equipment_type: equipment[];
};

type equipment = {
    cat_id: number;
    equip_id: number;
    name: string;
};
