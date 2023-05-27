interface deletedItem {
    item_id: number;
    item_name: string;
    item_deleted: string;
}

interface deletedItemsCategory {
    equipment: deletedItem[];
    contacts: deletedItem[];
}