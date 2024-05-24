interface TimezoneList {
    [key: string]: {
        [key: string]: string;
    };
}

type logLine = {
    date: string;
    details: string[];
    env: string;
    level: string;
    message: string;
    time: string;
    stack_trace?: string[];
};

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
