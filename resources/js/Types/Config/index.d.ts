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