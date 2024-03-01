type customer = {
    cust_id: number;
    primary_site_id: number;
    name: string;
    dba_name: string | null;
    slug: string;
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
    // contacts: customerContact[];
    // notes: customerNote[];
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
    cust_equip_id: number;
    equip_name: string;
    // customer_equipment_data: customerEquipmentData[];
};

type customerEquipmentData = {
    id: number;
    order: number;
    field_name: string;
    value: string;
    data_field_type: dataTypes;
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
