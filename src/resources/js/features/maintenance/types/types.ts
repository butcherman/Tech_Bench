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
            ip_address?: string;
            trace_id?: string;
            user?: User;
        } | null;
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
