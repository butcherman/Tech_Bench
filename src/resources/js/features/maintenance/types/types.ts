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
        per_page: number;
        to: number;
    };
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
