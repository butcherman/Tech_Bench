interface TimezoneList {
    [key: string]: {
        [key: string]: string;
    };
}

type logLevel = {
    name: string;
    icon: string;
    color: string;
};

type logChannel = {
    name: string;
    folder: string;
    channel: string;
};
