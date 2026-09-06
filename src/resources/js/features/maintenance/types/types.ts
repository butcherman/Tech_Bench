interface LogEntry {
    time: string;
    env: string;
    level: string;
    message: string;
    data: any;
    context: any;
}

type LogLevel =
    | "emergency"
    | "alert"
    | "critical"
    | "error"
    | "warning"
    | "notice"
    | "info"
    | "debug";
