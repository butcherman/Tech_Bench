type SubmitMethod = "get" | "post" | "put" | "delete";

type ComponentSize = "sm" | "md" | "lg";

type VariantType =
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

interface MenuItem {
    label: string;
    icon: string;
    route:
        | string
        | {
              url: string;
              method: SubmitMethod;
          };
}

interface LinkList {
    url: string;
    text: string;
}

interface IndexedData<TData> {
    id: string;
    isFirst: boolean;
    isLast: boolean;
    data: TData;
}

interface LinkHelper {
    href: string;
    external?: boolean;
}

type Placement = "top" | "bottom" | "left" | "right";

interface FlashAlert {
    id?: string;
    message: string;
    variant: VariantType;
}

interface ToastAlert {
    id?: string;
    title: string;
    message: string;
    href?: string;
}
