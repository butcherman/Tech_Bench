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
    idle_timeout: number;
    navbar: navbar[];
    breadcrumbs: breadcrumbs[];
    errors: { [key: string]: string }[];
};

type appProps = {
    name: string;
    company_name: string;
    logo: string;
    version: string;
    copyright: string;
    fileData: fileData;
};

type navbar = {
    name: string;
    route: string;
    icon: string;
};

type flashData = {
    id?: string;
    type: string;
    message: string;
};

type toastData = {
    id: string;
    title: string;
    message: string;
    href?: string;
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

type fileData = {
    chunkSize: number;
    maxSize: number;
    token: string;
};

type fileUpload = {
    file_id: number;
    file_name: string;
    file_size: number;
    href: string;
    created_stamp: string;
};

interface tableColumn {
    label: string;
    field: string;
    sort?: boolean;
    sortField?: string;
    isBoolean?: boolean;
    filterOptions?: {
        enabled: boolean;
        placeholder?: string;
    };
    icon?: string;
    textVariant?: string;
}
