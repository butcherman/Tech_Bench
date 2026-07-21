import "@inertiajs/core";

declare module "@inertiajs/core" {
    export interface InertiaConfig {
        sharedPageProps: {
            current_user: User | null;
            csrf_token: string;
            navbar: menuItem[];
            breadcrumbs: {
                title: string;
                url: string;
                is_current_page: boolean;
            }[];
        };
        // flashDataType: {
        //     toast?: { type: "success" | "error"; message: string };
        // };
        // errorValueType: string[];
        // layoutProps: {
        //     title: string;
        //     showSidebar: boolean;
        // };
        // namedLayoutProps: {
        //     app: { title: string; theme: "light" | "dark" };
        //     content: { padding: string; maxWidth: string };
        // };
    }
}
