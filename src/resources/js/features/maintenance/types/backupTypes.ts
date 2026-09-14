interface BackupStatus {
    healthy: boolean;
    latest?: BackupInfo;
    backup_count: number;
    total_size: number;
    message: string | null;
}

interface BackupInfo {
    backup_name: string;
    completed_at: string;
    duration: number;
    error: string | null;
    size: number;
    started_at: string;
    status: "running" | "completed" | "failed";
    type: "scheduled" | "manual" | "cli" | "unknown";
}

interface BackupStrategy {
    daily: number;
    weekly: number;
    monthly: number;
    yearly: number;
}

interface BackupSettings {
    nightly_backup: boolean;
    nightly_cleanup: boolean;
    encryption: boolean;
    password: string | null;
}
