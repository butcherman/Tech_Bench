interface LogEntry {
    timestamp: string;
    env: string;
    level: string;
    user: string | null;
    data: {
        body: string;
        extra: unknown[];
        stack_trace: string | string[];
        context: {
            ip_address: string;
            trace_id: string;
            user: User;
        };
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
