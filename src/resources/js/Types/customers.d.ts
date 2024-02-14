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
    cust_id: number;
    site_name: string;
    site_slug: string;
    address: string;
    city: string;
    state: string;
    zip: number;
};

/*******************************************************************************
 *                          Customer Misc
 *******************************************************************************/
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
