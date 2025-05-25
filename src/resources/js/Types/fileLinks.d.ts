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
    cust_id?: number;
};

type fileLinkTimeline = {
    added_by: string | number;
    created_at: string;
    notes: fileLinkNote | null;
    files: fileUpload[];
    link_id: number;
    timeline_id: number;
};

type fileLinkFile = {
    pivot: {
        upload: boolean;
        created_at: string;
        updated_at: string;
        timeline_id: number;
        moved: boolean;
    };
} & fileUpload;

type fileLinkNote = {
    created_at: string;
    link_note_id: number;
    note: string;
    timeline_id: number;
};
