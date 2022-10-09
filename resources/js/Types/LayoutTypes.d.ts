import type { Page, PageProps } from '@inertiajs/inertia';

export interface pageInterface extends Page<PageProps> {
    app          : appProps;
    flash        : flashProps;
    notifications: notifciationProps;
    navbar       : navBarProps[];
}

interface appProps {
    name     : string;
    logo     : string;
    version  : string;
    copyright: string;
    user     : userType;
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

export interface navBarProps {
    name : string;
    route: string;
    icon : string;
}

interface userType {
    email     : string;
    first_name: string;
    full_name : string;
    initials  : string;
    last_name : string;
    username  : string;
}

interface fileDataType {
    chunkSize: number;
    maxSize  : number;
    token    : string;
}
