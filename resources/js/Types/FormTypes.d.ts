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
