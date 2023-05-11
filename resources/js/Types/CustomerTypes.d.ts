import { string } from 'yup';
import type { Ref } from "vue";
import { customerEquipmentType } from "@/Types";

interface basicPermissionsType {
    create: boolean;
    update: boolean;
    delete: boolean;
}

export interface customerPermissionType {
    details: {
        create: boolean;
        update: boolean;
        delete: boolean;
        manage: boolean;
    };
    equipment: basicPermissionsType;
    contact: basicPermissionsType;
    notes: basicPermissionsType;
}

export interface customerFileType {
    description: string;
    file_type_id?: number;
}

export interface customerType {
    address: string;
    child_count: number;
    city: string;
    cust_id: number;
    dba_name: string | null;
    name: string;
    parent_id: number | null;
    parent: customerType | null;
    slug: string;
    state: string;
    zip: number;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    customer_equipment: customerEquipmentType[];
    parent_equipment: customerEquipmentType[];
}

export interface customerSearchParamType {
    name: string | null;
    city: string | null;
    equip: string | null;
    page: number;
    perPage: number;
    sortField: string;
    sortType: "asc" | "desc";
}

export interface customerSearchType {
    data: customerType[];
    currentPage: number;
    numPages: number;
    listFrom: number;
    listTo: number;
    listTotal: number;
    pageArr: number[];
}

export interface customerPaginationType {
    currentPage: number;
    numPages: number;
    listFrom: number;
    listTo: number;
    listTotal: number;
    pageArr: number[];
}

/**
 * Customer Contact Information
 */
export interface contactPhoneType {
    cont_id: number;
    extension: string | null;
    formatted: string;
    id: number;
    phone_number: number;
    phone_number_type: {
        description: string;
        icon_class: string;
    };
}

export interface customerContactType {
    cont_id: number;
    cust_id: number;
    shared: boolean;
    name: string;
    email: string | null;
    title: string | null;
    note: string | null;
    customer_contact_phone: contactPhoneType[];
}

export interface customerNoteType {
    subject: string;
    details: string;
    shared: boolean;
    urgent: boolean;
    created_at?: string;
    updated_at?: string;
    author?: string;
    updated_author?: string;
    note_id?: number;
    cust_id?: number;
}
