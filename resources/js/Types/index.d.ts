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
    alerts: alert[];
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
    read_at: string | null;
    data: {
        component: string;
        subject: string;
        props: object;
    };
};

type notificationBroadcast = {
    component: string;
    id: string;
    props: object;
    subject: string;
    type: string;
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

type alert = {
    type: string;
    html?: string;
    message?: string;
}
