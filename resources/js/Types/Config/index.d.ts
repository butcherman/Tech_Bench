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