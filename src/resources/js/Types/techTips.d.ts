type techTips = {
    tip_id: number;
    user_id: number;
    updated_id: number;
    tip_type_id: number;
    sticky: boolean;
    subject: string;
    slug: string;
    details: string;
    views: number;
    deleted_at: string;
    created_at: string;
    updated_at: string;
};

type tipType = {
    tip_type_id: number;
    description: string;
};
