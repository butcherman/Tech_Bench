import type { Page, PageProps } from '@inertiajs/inertia';

export interface pageInterface extends Page<PageProps> {
    app          : appProps;
    errors       : any;
    flash        : flashProps;
    notifications: notifciationProps;
    navbar       : navBarProps[];
    breadcrumbs  : breadcrumbsType[];
}

interface appProps {
    name     : string;
    logo     : string;
    version  : string;
    copyright: string;
    user     : userType | null;
    fileData : fileDataType;
}

interface flashProps {
    warning: string | null;
    success: string | null;
}

interface notifciationProps {
    list: object[];     //  TODO - type this
    new : number;
}

interface navBarProps {
    name : string;
    route: string;
    icon : string;
}

interface breadcrumbsType {
    title          : string;
    url            : string;
    is_current_page: boolean;
}

export interface userType {
    email     : string;
    first_name: string;
    full_name : string;
    initials  : string;
    last_name : string;
    username  : string;
    user_id  ?: number;
}

export interface settingsType {
    setting_type_id  : number;
    value            : boolean;
    user_setting_type: {
        name        : string;
        perm_type_id: number | null;
    }
}

interface fileDataType {
    chunkSize: number;
    maxSize  : number;
    token    : string;
}
