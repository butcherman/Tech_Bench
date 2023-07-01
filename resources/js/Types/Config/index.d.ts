type appConfig = {
    url: string;
    timezone: string;
    max_filesize: number;
}

type tzList = {
    [key: string]: {
        [key: string]: string;
    }
}

type emailSettings = {
    host: string;
    port: number;
    encryption: string;
    username: string;
    password: string;
    from_address: string;
    require_auth: boolean;
}

type logLevels = {
    name: string;
    icon: string;
    color: string;
}

type logStats = {
    filename: string;
    total: number;
    alert: number;
    critical: number;
    debug: number;
    emergency: number;
    error: number;
    info: number;
    notice: number;
    warning: number;
}

type logFile = {
    date: string;
    time: string;
    env: string;
    level: string;
    message: string;
    details?: any | any[];
}

type logChannels = {
    channel: string;
    folder: string;
    name: string;
}

type oathConfig = {
    allow_login: boolean;
    allow_register: boolean;
    tenant: string | null;
    client_id: string | null;
    client_secret: string | null;
    redirectUri: string | null;
}

type twoFaConfig = {
    required: boolean;
    allow_bypass: boolean;
    allow_save_Device: boolean;
    allow_via_email: boolean;
    allow_via_sms: boolean;
}