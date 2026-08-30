interface TimezoneOption {
    label: string;
    value: string;
}

interface TimezoneList {
    label: string;
    items: TimezoneOption[];
}

interface SslCertificateData {
    is_valid: boolean;
    issuer: string;
    expires: string;
    signature: string;
    organization: string;
}

interface AppFeatureList {
    file_links: boolean;
    enable_workbooks: boolean;
    public_tips: boolean;
    tip_comments: boolean;
}
