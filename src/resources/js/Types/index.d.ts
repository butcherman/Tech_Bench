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
    navbar: navbar[];
    // errors: errors;
    // notifications: notificationProps;
    // breadcrumbs: breadcrumbs[];
    // alerts: alert[];
    // stepId?: number;
};

type appProps = {
    name: string;
    logo: string;
    // version: string;
    // copyright: string;
    // user: user | null;
    // fileData: fileData;
};

type navbar = {
    name: string;
    route: string;
    icon: string;
};

type flashData = {
    type: string;
    message: string;
};
