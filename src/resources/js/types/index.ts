interface FlashAlert {
    id?: string;
    message: string;
    level: FlashLevel;
}

type FlashLevel =
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

type variantType =
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

type componentSize = "small" | "normal" | "large";

interface InertiaFormData {
    [key: string]: any;
}

interface InertiaFormErrors {
    [key: string]: string;
}

interface linkList {
    url: string;
    text: string;
}

interface menuItem {
    label: string;
    icon: string;
    route: string;
}
