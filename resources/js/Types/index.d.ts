/**
 * General/Global Types
 */
interface ComponentInternalInstance {
    $route: () => string;
}

type pageData = {
    props: props;
};

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
    status: string | null;
};

type notificationProps = {
    list: notification[];
    new: number;
};

type notification = {
    created_at: string;
    id: string;
    notifiable_id: number;
    notifiable_type: string;
    read_at: string | null;
    type: string;
    updated_at: string;
    data: {
        component: string;
        subject: string;
        props: object;
    }
}

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

type user = {
    email: string;
    first_name: string;
    full_name: string;
    initials: string;
    last_name: string;
    username: string;
};
