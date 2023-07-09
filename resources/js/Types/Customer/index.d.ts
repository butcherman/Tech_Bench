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
};