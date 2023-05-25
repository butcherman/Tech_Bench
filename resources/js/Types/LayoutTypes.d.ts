// import type { userType } from '@/Types';
// import type { Page, PageProps } from '@inertiajs/core';

// export type phoneNumberType = {
//     description: string;
//     icon_class: string;
// }

// export type pageData = Page & {
//         props: propsType;
// }

// export type propsType = PageProps & {
//     app          : appProps;
//     errors       : errorType;
//     flash        : flashProps;
//     notifications: notificationProps;
//     navBar       : navBarProps[];
//     breadcrumbs  : breadcrumbsType[];
// }

// export type appProps = {
//     name     : string;
//     logo     : string;
//     version  : string;
//     copyright: string;
//     user     : userType | null;
//     fileData : fileDataType;
// }

// type flashProps = {
//     success: string | null;
//     warning: string | null;
//     danger : string | null;
//     info   : string | null;
// }

// export type flashMessage = {
//     type   : string;
//     message: string;
// }

// type notificationProps = {
//     list: object[];     //  TODO - type this
//     new : number;
// }

// type navBarProps = {
//     name : string;
//     route: string;
//     icon : string;
// }

// type breadcrumbsType = {
//     title          : string;
//     url            : string;
//     is_current_page: boolean;
// }

// export type fileDataType = {
//     chunkSize: number;
//     maxSize  : number;
//     token    : string;
// }

// interface errorType {
//     [key:string]: string;
// }
