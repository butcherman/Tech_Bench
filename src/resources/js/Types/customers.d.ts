type customer = {
    cust_id: number;
    primary_site_id: number;
    name: string;
    dba_name: string | null;
    slug: string;
    site_count: number;
    customer_site: customerSite[];
};

type customerSite = {
    cust_site_id: number;
    is_primary: boolean;
    cust_id: number;
    site_name: string;
    site_slug: string;
    address: string;
    city: string;
    state: string;
    zip: number;
};

type customerPageProps = {
    permissions: customerPermissions;
    customer: customer;
    site: customerSite;
    siteList: customerSite[];
    alerts: customerAlert[];
    equipmentList: customerEquipment[];
    contacts: customerContact[];
    notes: customerNote[];
    // files: customerFile[];
    // phoneTypes: string[];
    // equipTypes: { [key: string]: equipment[] };
    // fileTypes: fileTypes[];
} & pageProps;

/*******************************************************************************
 *                          Customer Equipment
 *******************************************************************************/
type customerEquipment = {
    cust_id: number;
    equip_id: number;
    cust_equip_id: number;
    equip_name: string;
};

type customerEquipmentData = {
    id: number;
    order: number;
    field_name: string;
    value: string;
    data_field_type: dataTypes;
};

/*******************************************************************************
 *                          Customer Contacts
 *******************************************************************************/
type customerContact = {
    cont_id: number;
    cust_id: number;
    local: boolean;
    decision_maker: boolean;
    name: string;
    email: string | null;
    title: string | null;
    note: string | null;
    customer_contact_phone: contactPhone[];
    customer_site: customerSite[];
};

type contactPhone = {
    cont_id: number;
    extension: string | null;
    formatted: string;
    id: number;
    phone_number: number;
    phone_number_type: phoneType;
};

type phoneType = {
    description: string;
    icon_class: string;
};

/*******************************************************************************
 *                          Customer Notes
 *******************************************************************************/
type customerNote = {
    note_id: number;
    subject: string;
    urgent: boolean;
    site_list: customerSite[];
    cust_equip_id: number;
    details: string;
    author: string;
    updated_author: string;
    updated_at: string;
    created_at: string;
    equipment_type: customerEquipment;
    customer_site: customerSite[];
};

/*******************************************************************************
 *                          Customer Misc
 *******************************************************************************/
type customerPermissions = {
    details: basicPermissions & { manage: boolean };
    equipment: basicPermissions;
    contact: basicPermissions;
    notes: basicPermissions;
    files: basicPermissions;
};

type customerAlert = {
    alert_id: number;
    cust_id: number;
    message: string;
    type: string;
};
