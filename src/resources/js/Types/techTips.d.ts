type techTip = {
    allow_comments: boolean;
    created_at: string;
    details: string;
    href: string;
    public: boolean;
    public_href: string;
    slug: string;
    sticky: boolean;
    subject: string;
    tip_id: number;
    type: string;
    updated_at: string;
    updated_id: number;
    user_id: number;
    views: number;
    created_by?: user;
    updated_by?: user;
    tip_type_id?: number;
};

type tipType = {
    tip_type_id: number;
    description: string;
};

type techTipComment = {
    comment_id: number;
    tip_id: number;
    user_id: number;
    author: string;
    comment: string;
    is_flagged: boolean;
    created_at: string;
    updated_at: string;
};

type techTipPermissions = {
    comment: boolean;
    public: boolean;
    manage: boolean;
} & basicPermissions;
