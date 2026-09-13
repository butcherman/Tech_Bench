interface BackupStatus {
    healthy: boolean;
    latest: BackupInfo;
    backup_count: number;
    total_size: number;
    message: string | null;
}

interface BackupInfo {
    name: string;
    size: number;
    modified: number;
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
