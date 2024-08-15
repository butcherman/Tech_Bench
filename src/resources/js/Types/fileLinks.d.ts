type fileLink = {
    link_id: number;
    link_hash: string;
    link_name: string;
    href: string;
    public_href: string;
    expire: string;
    is_expired: boolean;
    instructions: string | null;
    allow_upload: boolean;
    created_at: string;
    updated_at: string;
};

type fileLinkFile = {
    added_by: string | null;
    created_at: string;
    file_id: number;
    link_file_id: number;
    link_id: number;
    updated_at: number;
    upload: boolean;
};

type fileLinkUpload = {
    pivot: fileLinkFile;
} & fileUpload;
