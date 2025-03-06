/*
|-------------------------------------------------------------------------------
| Inertia Page
|-------------------------------------------------------------------------------
*/
type pageData = {
    props: pageProps;
};

type pageProps = {
    breadcrumbs: {
        title: string;
        url: string;
        is_current_page: boolean;
    }[];
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
