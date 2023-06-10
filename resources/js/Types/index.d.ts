/**
 * General/Global Types
 */
interface ComponentInternalInstance {
    $route: () => string;
}

type appProps = {
    name: string;
    logo: string;
    version: string;
    copyright: string;
    // user: user | null;
    // fileData: fileData;
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
