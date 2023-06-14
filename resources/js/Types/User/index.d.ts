type user = {
    email: string;
    first_name: string;
    full_name: string;
    initials: string;
    last_name: string;
    username: string;
    role_id: number;
};

type userSettings = {
    setting_type_id: number;
    value: boolean;
    name: string;
}

type baseUserRole = {
    name: string;
    description: string;
}

type userRole = {
    role_id: number;
    perm_type_id: number;
    allow_edit: boolean;
} & baseUserRole;