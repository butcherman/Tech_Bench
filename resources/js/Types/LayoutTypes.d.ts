import type { userType } from '@/Types';
import type { Page, PageProps } from '@inertiajs/vue3';

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
    success: string | null;
    warning: string | null;
    danger : string | null;
    info   : string | null;
}

export interface flashMessageType {
    type   : string;
    message: string;
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

interface fileDataType {
    chunkSize: number;
    maxSize  : number;
    token    : string;
}

interface errorType {
    [key:string]: string;
}