export interface userType {
    email     : string;
    first_name: string;
    full_name : string;
    initials  : string;
    last_name : string;
    username  : string;
}

export interface userAuthType extends userType {
    user_id : number;
    role_id : number;
}

export interface userRoleType {
    role_id    : number;
    name       : string;
    description: string;
}

export interface settingsType {
    setting_type_id  : number;
    value            : boolean;
    user_setting_type: {
        name        : string;
        perm_type_id: number | null;
    }
}