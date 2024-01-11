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
    // errors: errors;
    // flash: flashProps;
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
