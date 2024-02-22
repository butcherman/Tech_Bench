/**
 * Global Types
 */
type pageData = {
    props: pageProps;
};

type pageProps = {
    app: appProps;
    flash: flashData[];
    current_user: user | null;
    user_notifications: userNotificationProp;
    idle_timeout: number;
    navbar: navbar[];
    breadcrumbs: breadcrumbs[];
    errors: { [key: string]: string }[];
    // notifications: notificationProps;
    // alerts: alert[];
    // stepId?: number;
};

type appProps = {
    name: string;
    logo: string;
    version: string;
    copyright: string;
    current_route: string;
    // fileData: fileData;
};

type navbar = {
    name: string;
    route: string;
    icon: string;
};

type flashData = {
    id: string;
    type: string;
    message: string;
};

type toastData = {
    id: string;
    title: string;
    message: string;
};

type breadcrumbs = {
    title: string;
    url: string;
    is_current_page: boolean;
};

type basicPermissions = {
    create: boolean;
    update: boolean;
    delete: boolean;
};
