interface LogEntry {
    timestamp: string;
    env: string;
    level: LogLevel;
    user: string | null;
    data: {
        body: string;
        extra: unknown[] | null;
        stack_trace: string[];
        context: {
            ip_address: string;
            trace_id: string;
            user: string;
            user_id: number;
        } | null;
    };
}

interface LogData {
    data: LogEntry[];
    meta: {
        current_page: number;
        from: number;
        has_more: boolean;
        to: number;
        snapshot: number;
    };
}

interface LogStats {
    total: number;
    levels: {
        [key in LogLevel]: number;
    };
    traceIds: {
        [key: string]: number;
    };
    userList: {
        [key: string]: number;
    };
}

interface LogFilter {
    search: string;
    logFile: string;
    level: LogLevel | "All";
    user: string;
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
