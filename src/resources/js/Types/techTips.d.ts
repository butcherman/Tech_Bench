type techTip = {
    tip_id: number;
    user_id: number;
    updated_id: number;
    tip_type_id: number;
    sticky: boolean;
    public: boolean;
    allow_comments: boolean;
    subject: string;
    slug: string;
    details: string;
    views: number;
    deleted_at: string;
    created_at: string;
    updated_at: string;
    created_by: user;
    updated_by: user;
    equipList?: number[];
    fileList?: number[];
    file_upload?: fileUpload[];
};

type tipType = {
    tip_type_id: number;
    description: string;
};

type tipComment = {
    comment_id: number;
    tip_id: number;
    user_id: number;
    author: string;
    comment: string;
    flagged: boolean;
    created_at: string;
    updated_at: string;
    tech_tip?: techTip;
};

type techTipPermissions = {
    comment: boolean;
    manage: boolean;
} & basicPermissions;
