/**
 * Form types that will be used more than once are exported here
 */
export interface userFormType {
    username  : string;
    first_name: string;
    last_name : string;
    email     : string;
    role_id   : number;
}

export type BaseRoleFormType = {
    name       : string;
    description: string;
}

export type RoleFormType = {
    [key:string]: boolean | string;
} & BaseRoleFormType;

export interface equipmentFormType {
    category: string;
    name    : string;
    custData: string[];
}

export interface customerFormType {
    cust_id  : number;
    parent_id: string;
    name     : string;
    dbaName  : string;
    address  : string;
    city     : string;
    state    : string;
    zip      : number;
}
