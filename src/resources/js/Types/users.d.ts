type user = {
    user_id: number;
    username: string;
    email: string;
    first_name: string;
    last_name: string;
    full_name: string;
    initials: string;
    role_id: string;
    user_role: userRole;
    created_at: string;
    updated_at: string;
    deleted_at: string;
};

type userNotificationProp = {
    list: userNotification[];
    new: number;
};

type userNotification = {
    created_at: string;
    id: string;
    read_at: string | null;
    data: {
        component: string;
        subject: string;
        props: object;
    };
};

type userRole = {
    role_id: number;
    name: string;
    description: string;
    allow_edit: boolean;
};

type userRolePermission = {
    perm_type_id: number;
    description: string;
    group: string;
    allow: boolean;
    feature_enabled: boolean;
};

type userRolePermissionGroup = { [key: string]: userRolePermission[] };

type userDevice = {
    device_id: number;
    type: string;
    os: string;
    browser: string;
    registered_ip_address: string;
    updated_ip_address: string;
    created_at: string;
    updated_at: string;
};

type userSettings = {
    setting_type_id: number;
    value: boolean;
    name: string;
};

type passwordPolicy = {
    expire: number;
    min_length: number;
    contains_uppercase: boolean;
    contains_lowercase: boolean;
    contains_number: boolean;
    contains_special: boolean;
    disable_compromised: boolean;
};
