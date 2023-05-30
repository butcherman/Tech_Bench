type levels = {
    icon : string;
    name : string;
}

type logFiles = {
    alert    : number;
    critical : number;
    debug    : number;
    emergency: number;
    error    : number;
    info     : number;
    notice   : number;
    warning  : number;
    total    : number;
    filename : string;
}

type logDetails = {
    date        : string;
    details    ?: any | null;
    env         : string;
    level       : string;
    message     : string;
    time        : string;
    stack_trace?: string[]
}

type logForm = {
    days : string;
    level: string;
}