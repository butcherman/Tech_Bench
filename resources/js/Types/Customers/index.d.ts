/**
 * Types and Interfaces for all customer related ts
 */

interface isCustValid {
    valid: boolean;
    name: string;
}

type customer = {
    address: string;
    child_count: number;
    city: string;
    cust_id: number;
    dba_name: string | null;
    name: string;
    parent_id: number | null;
    parent: customer | null;
    slug: string;
    state: string;
    zip: number;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    customer_equipment: customerEquipment[];
    parent_equipment: customerEquipment[];
};

type customerEquipment = {
    cust_equip_id: number;
    shared: boolean;
    customer_equipment_data: customerEquipmentData[];
} & equipment;

type customerEquipmentData = {
    [key: string]: string | boolean;
};

type customerContact = {
    cont_id: number;
    cust_id: number;
    shared: boolean;
    name: string;
    email: string | null;
    title: string | null;
    note: string | null;
    customer_contact_phone: contactPhone[];
};

type customerNote = {
    subject: string;
    details: string;
    shared: boolean;
    urgent: boolean;
    created_at?: string;
    updated_at?: string;
    author?: string;
    updated_author?: string;
    note_id?: number;
    cust_id?: number;
};

type contactPhone = {
    cont_id: number;
    extension: string | null;
    formatted: string;
    id: number;
    phone_number: number;
    phone_number_type: phoneNumber;
};

type phoneNumber = {
    description: string;
    icon_class: string;
}

type customerFile = {
    cust_id?: number;
    cust_file_id: number;
    file_id: number;
    shared: boolean;
    name: string;
    updated_at: string;
    uploaded_by: string;
    file_type: string;
    file_upload: file
}

type fileTypes = {
    description: string;
    file_type_id: number;
}

type basicPermissions = {
    create: boolean;
    update: boolean;
    delete: boolean;
};

type customerPermissions = {
    details: basicPermissions & { manage: boolean };
    equipment: basicPermissions;
    contact: basicPermissions;
    notes: basicPermissions;
    files: basicPermissions;
};

type customerSearchParam = {
    name: string | null;
    city: string | null;
    equip: string | null;
    page: number;
    perPage: number;
    sortField: string;
    sortType: "asc" | "desc";
};

type customerSearch = {
    data: customer[];
    currentPage: number;
    numPages: number;
    listFrom: number;
    listTo: number;
    listTotal: number;
    pageArr: number[];
};

type customerPagination = {
    currentPage: number;
    numPages: number;
    listFrom: number;
    listTo: number;
    listTotal: number;
    pageArr: number[];
};

type customerSearchDataSymbol = {
    searchParam: customerSearchParam;
    paginationData: customerPagination;
    paginationArray: number[];
    triggerSearch: () => void;
    resetSearch: () => void;
}

interface deletedItem {
    item_id: number;
    item_name: string;
    item_deleted: string;
}

interface deletedItemsCategory {
    equipment: deletedItem[];
    contacts: deletedItem[];
}

type linkForm = {
    cust_id: number | undefined;
    parent_id: number | null;
    add: boolean;
};