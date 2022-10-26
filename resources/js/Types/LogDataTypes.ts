export interface levelsType {
    icon : string;
    name : string;
}

export interface logFilesType {
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

export interface logDetailsType {
    date        : string;
    details    ?: any | null;
    env         : string;
    level       : string;
    message     : string;
    time        : string;
    stack_trace?: string[]
}
