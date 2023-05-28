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
} & equipType;

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
