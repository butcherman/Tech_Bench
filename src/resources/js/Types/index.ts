/*
|-------------------------------------------------------------------------------
| Inertia Page
|-------------------------------------------------------------------------------
*/
type pageData = {
    props: pageProps;
};

type pageProps = {
    csrf_token: string;
    idle_timeout: number;
    errors: { [key: string]: string }[];
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
