/**
 * Global Types
 */

interface ComponentInternalInstance {
    $route: () => string;
}

type pageData = {
    props: pageProps;
};

type pageProps = {
    app: appProps;
    flash: flashData[];
    // errors: errors;
    // notifications: notificationProps;
    // navbar: navBarProps[];
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

type flashData = {
    type: string;
    message: string;
};
