import { equipType } from '@/Types';

export interface techTipType {
    created_at: string;
    details   : string;
    slug      : string;
    sticky    : boolean;
    subject   : string;
    summary   : string;
    tip_id    : number;
    updated_at: string;
    views     : number;
}

export type techTipWithEquipment = {
    equipment_type: equipType;
} & techTipType;
