interface User {
    email: string;
    first_name: string;
    full_name: string;
    initials: string;
    last_name: string;
    role_name: string;
    role_id: string;
    username: string;
    two_factor_confirmed_at: string | null;
    two_factor_via: string | null;
    deleted_at?: string;
    created_at?: string;
    updated_at?: string;
    // user_role?: UserRole;
}

interface UserRole {
    allow_edit: boolean;
    description: string;
    name: string;
    role_id: number;
}

interface UserSettings {
    setting_type_id: number;
    value: boolean;
    name: string;
    description: string;
}

interface UserRolePermission {
    perm_type_id: number;
    description: string;
    group: string;
    allow: boolean;
    feature_enabled: boolean;
}

type UserRolePermissionCategory = { [key: string]: UserRolePermission[] };

interface UserDevice {
    device_id: number;
    type: string;
    os: string;
    browser: string;
    registered_ip_address: string;
    updated_ip_address: string;
    created_at: string;
    updated_at: string;
}
