interface twoFaConfig {
    required: boolean;
    allow_save_device: boolean;
}

interface oathConfig {
    allow_login: boolean;
    allow_register: boolean;
    default_role_id: number;
    tenant: string;
    client_id: string;
    client_secret: string;
    secret_expires: string;
    redirect: string;
}
