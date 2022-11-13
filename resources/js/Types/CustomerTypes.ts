export interface customerPermissionType {
    create?: boolean;
    update?: boolean;
    disable?: boolean;
}

export interface customerType {
    address            : string;
    child_count        : number;
    city               : string;
    cust_id            : number;
    dba_name           : string | null;
    name               : string;
    parent_id          : number | null;
    slug               : string;
    state              : string;
    zip                : number;
    customer_equipment?: any; //  FIXME - type this
    parent_equipment  ?: any; //  FIXME - type this
}

export interface customerSearchParamType {
    name     : string | null;
    city     : string | null;
    equip    : string | null;
    page     : number;
    perPage  : number;
    sortField: string;
    sortType : 'asc' | 'desc';
}

export interface customerPaginationType {
    currentPage: number;
    numPages   : number;
    listFrom   : number;
    listTo     : number;
    listTotal  : number;
    pageArr    : number[]
}
