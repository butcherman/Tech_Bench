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
    | "warning"
    | "none";

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
interface laravelValidationErrors {
    errors: { [key: string]: string[] };
    message: string;
}

type fileData = {
    chunkSize: number;
    maxSize: number;
};

type fileUpload = {
    file_id: number;
    file_name: string;
    file_size: number;
    href: string;
    created_stamp: string;
};

type phoneType = {
    description: string;
    icon_class: string;
};
