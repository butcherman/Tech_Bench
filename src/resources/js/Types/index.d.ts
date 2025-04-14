/*
|-------------------------------------------------------------------------------
| Inertia Page
|-------------------------------------------------------------------------------
*/
type pageData = {
    props: pageProps;
};

type pageProps = {
    breadcrumbs: breadcrumb[];
    csrf_token: string;
    current_user: user;
    errors: { [key: string]: string }[];
    flash: flashData[];
    idle_timeout: number;
    navbar: navbarItem[];
};

/*
|-------------------------------------------------------------------------------
| Page Layout Elements
|-------------------------------------------------------------------------------
*/
type navbarItem = {
    name: string;
    route: string;
    icon: string;
};

type breadcrumb = {
    title: string;
    url: string;
    is_current_page: boolean;
};

type flashData = {
    id: string;
    type: elementVariant;
    message: string;
};

/*
|-------------------------------------------------------------------------------
| Shared Types
|-------------------------------------------------------------------------------
*/
type elementVariant =
    | "danger"
    | "dark"
    | "error"
    | "help"
    | "info"
    | "light"
    | "primary"
    | "secondary"
    | "success"
    | "warning";

type basicPermissions = {
    create: boolean;
    update: boolean;
    delete: boolean;
};

/*
|-------------------------------------------------------------------------------
| Misc
|-------------------------------------------------------------------------------
*/
type fileData = {
    chunkSize: number;
    maxSize: number;
};
