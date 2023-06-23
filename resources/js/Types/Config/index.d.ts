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