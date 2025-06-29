type customer = {
    cust_id: number;
    dba_name: string | null;
    name: string;
    primary_site_id: number;
    site_count: number;
    sites: customerSite[];
    slug: string;
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
    currentSite: customerSite;
    siteList: customerSite[];
    isFav: boolean;
    alerts: customerAlert[];
    equipmentList: customerEquipment[];
    groupedEquipmentList: { [key: string]: customerEquipment[] }[];
    contactList: customerContact[];
    noteList: customerNote[][];
    fileList: customerFile[];
    phoneTypes: phoneType[];
    fileTypes: customerFileType[];
    allowVpn: boolean;
    vpnData: vpnData;
    allowShareVpn: boolean;
    equipment: customerEquipment;
} & pageProps;

/*
|-------------------------------------------------------------------------------
| Customer Equipment
|-------------------------------------------------------------------------------
*/
type customerEquipment = {
    cust_id: number;
    equip_id: number;
    cust_equip_id: number;
    equip_name: string;
    sites?: customerSite[];
};

type customerEquipmentData = {
    id: number;
    order: number;
    field_name: string;
    value: string;
    data_field_type: dataTypes;
};

type vpnData = {
    vpn_id: number;
    vpn_client_name: string;
    vpn_portal_url: string;
    vpn_username: string | null;
    vpn_password: string | null;
    notes: string | null;
};

/*
|-------------------------------------------------------------------------------
| Customer Contacts
|-------------------------------------------------------------------------------
*/
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
    sites: customerSite[];
};

type contactPhone = {
    cont_id: number;
    extension: string | null;
    formatted: string;
    id: number;
    phone_number: number;
    phone_number_type: phoneType;
};

/*
|-------------------------------------------------------------------------------
| Customer Notes
|-------------------------------------------------------------------------------
*/
type customerNote = {
    note_id: number;
    subject: string;
    urgent: boolean;
    cust_equip_id: number;
    details: string;
    author: string;
    updated_author: string;
    updated_at: string;
    created_at: string;
    customer_equipment: customerEquipment;
    sites: customerSite[];
    note_type: "Site" | "equipment" | "general";
};

/*
|-------------------------------------------------------------------------------
| Customer Files
|-------------------------------------------------------------------------------
*/
type customerFile = {
    cust_file_id: number;
    file_id: number;
    name: string;
    created_at: string;
    created_stamp: string;
    file_type: string;
    file_type_id: number;
    uploaded_by: string;
    href: string;
    equip_name: string;
    cust_equip_id: number;
    sites: customerSite[];
    file_upload: fileUpload;
    file_category: "general" | "site" | "equipment";
};

type customerFileType = {
    file_type_id: number;
    description: string;
};

/*
|-------------------------------------------------------------------------------
| Customer Misc
|-------------------------------------------------------------------------------
*/

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
