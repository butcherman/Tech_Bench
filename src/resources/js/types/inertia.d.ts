import "@inertiajs/core";

declare module "@inertiajs/core" {
    export interface InertiaConfig {
        sharedPageProps: {
            current_user: User | null;
            csrf_token: string;
            navbar: menuItem[];
            // bookmarks: any[];
            // auth: { user: { id: number; name: string } | null };
            // appName: string;
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
