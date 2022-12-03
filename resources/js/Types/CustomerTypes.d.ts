import type { Ref }              from "vue";
import { customerEquipmentType } from '@/Types';

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
    }
    equipment: basicPermissionsType;
}

export interface customerFileType {
    description  : string;
    file_type_id?: number;
}

export interface customerType {
    address            : string;
    child_count        : number;
    city               : string;
    cust_id            : number;
    dba_name           : string | null;
    name               : string;
    parent_id          : number | null;
    parent             : customerType | null;
    slug               : string;
    state              : string;
    zip                : number;
    created_at        ?: string;
    updated_at        ?: string;
    deleted_at        ?: string;
    customer_equipment?: customerEquipmentType;
    parent_equipment  ?: customerEquipmentType;
}

export interface customerSearchParamType {
    name     : string | null;
    city     : string | null;
    equip    : string | null;
    page     : number;
    perPage  : number;
    sortField: string;
    sortType : 'asc' | 'desc';
}

export interface customerSearchType {
    data       : customerType[],
    currentPage: number,
    numPages   : number,
    listFrom   : number,
    listTo     : number,
    listTotal  : number,
    pageArr    : number[],
}

export interface customerPaginationType {
    currentPage: number;
    numPages   : number;
    listFrom   : number;
    listTo     : number;
    listTotal  : number;
    pageArr    : number[]
}

/**
 * Provide/Inject Types
 */
export interface customerBookmarkInjection {
    isBookmark     : Ref<boolean>;
    bookmarkLoading: Ref<boolean>
    toggleBookmark : () => void;
}
