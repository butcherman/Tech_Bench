type techTip = {
    allow_comments: boolean;
    created_at: string;
    // created_by: user;
    details: string;
    equip_list: number[];
    equipment: equipment[];
    slug: string;
    sticky: boolean;
    subject: string;
    tip_id: number;
    tip_type_id: number;
    updated_id: number;
    user_id: number;
    public: boolean;
    //     views: number;
    //     deleted_at: string;
    //     updated_at: string;
    //     updated_by: user;
    //     equipList?: number[];
    //     fileList?: number[];
    //     file_upload?: fileUpload[];
    //     tech_tip_type: tipType;
    //     tech_tip_view: {
    //         tip_id: number;
    //         views: number;
    //     };
};

type tipType = {
    tip_type_id: number;
    description: string;
};

// type tipComment = {
//     comment_id: number;
//     tip_id: number;
//     user_id: number;
//     author: string;
//     comment: string;
//     is_flagged: boolean;
//     created_at: string;
//     updated_at: string;
//     tech_tip?: techTip;
//     flags?: {
//         comment_id: number;
//         created_at: string;
//         flagged_by: string;
//     }[];
// };

type techTipPermissions = {
    comment: boolean;
    public: boolean;
    manage: boolean;
} & basicPermissions;
