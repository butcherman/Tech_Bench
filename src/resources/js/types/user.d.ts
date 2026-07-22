type User = {
    user_id: number;
    username: string;
    email: string;
    first_name: string;
    last_name: string;
    full_name: string;
    initials: string;
    role_name: string;
    two_factor_confirmed_at: string | null;
    // role_id: string;
    // created_at: string;
    // updated_at: string;
    // deleted_at: string;
    // user_role: UserRole;
};

type UserRole = {
    role_id: number;
    name: string;
    description: string;
    allow_edit: boolean;
};

type UserSettings = {
    setting_type_id: number;
    value: boolean;
    name: string;
};
