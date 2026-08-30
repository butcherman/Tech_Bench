type MultiFactorMethod = "email" | "authenticator";

interface PasswordPolicy {
    expire: number;
    min_length: number;
    contains_uppercase: boolean;
    contains_lowercase: boolean;
    contains_number: boolean;
    contains_special: boolean;
    disable_compromised: boolean;
}

interface MultiFactorConfig {
    enabled: boolean;
    required: boolean;
    allow_save_device: boolean;
    allow_via_email: boolean;
    allow_via_authenticator: boolean;
}

interface OathConfig {
    allow_login: boolean;
    allow_register: boolean;
    default_role_id: number;
    tenant: string;
    client_id: string;
    client_secret: string;
    secret_expires: string;
    redirect: string;
}
