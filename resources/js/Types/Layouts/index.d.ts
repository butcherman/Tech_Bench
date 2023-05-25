// import { Page, PageProps } from "@inertiajs/core";

// export type flashMessage = {
type flashMessage = {
    type: string;
    message: string;
};

// export type pageData = Page & {
type pageData = {
    props: props;
};

// type props = PageProps & {
type props = {
    app: appProps;
    errors: errors;
    flash: flashProps;
    notifications: notificationProps;
    navbar: navBarProps[];
    breadcrumbs: breadcrumbs[];
};

type appProps = {
    name: string;
    logo: string;
    version: string;
    copyright: string;
    user: user | null;
    fileData: fileData;
};

interface errors {
    [key: string]: string;
}

type flashProps = {
    success: string | null;
    warning: string | null;
    danger: string | null;
    info: string | null;
};

type notificationProps = {
    list: object[]; //  TODO - type this
    new: number;
};

type navBarProps = {
    name: string;
    route: string;
    icon: string;
};

type breadcrumbs = {
    title: string;
    url: string;
    is_current_page: boolean;
};

type fileData = {
    chunkSize: number;
    maxSize: number;
    token: string;
};

// type user = {
//     email: string;
//     first_name: string;
//     full_name: string;
//     initials: string;
//     last_name: string;
//     username: string;
// };
