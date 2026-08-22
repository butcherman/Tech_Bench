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

// interface UserRole {
//     role_id: number;
//     name: string;
//     description: string;
//     allow_edit: boolean;
// }

// interface UserRolePermission {
//     perm_type_id: number;
//     description: string;
//     group: string;
//     allow: boolean;
//     feature_enabled: boolean;
// }

// interface PasswordPolicy {
//     expire: number;
//     min_length: number;
//     contains_uppercase: boolean;
//     contains_lowercase: boolean;
//     contains_number: boolean;
//     contains_special: boolean;
//     disable_compromised: boolean;
// }

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

// interface TwoFaConfig {
//     required: boolean;
//     allow_save_device: boolean;
//     allow_via_email: boolean;
//     allow_via_authenticator: boolean;
// }

// interface OathConfig {
//     allow_login: boolean;
//     allow_register: boolean;
//     default_role_id: number;
//     tenant: string;
//     client_id: string;
//     client_secret: string;
//     secret_expires: string;
//     redirect: string;
// }
