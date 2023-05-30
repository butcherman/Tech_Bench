type user = {
    email: string;
    first_name: string;
    full_name: string;
    initials: string;
    last_name: string;
    username: string;
};

type disabledUser = {
    user_id: number;
    deleted_at: string;
} & user;

type userAuth = {
    user_id: number;
    role_id: number;
} & user;

type userWithRole = {
    user_roles: {
        description: string;
        name: string;
        role_id: number;
    };
} & userAuth;

type baseUserRole = {
    name: string;
    description: string;
}

type userRole = {
    role_id: number;
    perm_type_id?: number;
    allow_edit?: boolean;
} & baseUserRole;

type roleFormType = {
    [key:string]: boolean | string;
};

type userRolePermissions = {
    allow: boolean;
    perm_type_id: number;
    role_id: number;
    user_role_permission_types: {
        description: string;
        group: string;
        perm_type_id: number;
        role_cat_id: number;
    };
};

type settings = {
    setting_type_id: number;
    value: boolean;
    user_setting_type: {
        name: string;
        perm_type_id: number | null;
    };
};
