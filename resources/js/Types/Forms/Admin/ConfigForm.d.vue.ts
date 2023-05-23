type settingsType = {
    url: string;
    timezone: string;
    filesize: number;
};

type timezoneType = {
    [key: string]: {
        [key: string]: string;
    };
};
