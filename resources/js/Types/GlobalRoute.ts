import type {
    Route,
    Router,
    RouteParam,
    RouteParamsWithQueryOverload,
    Config,
} from "ziggy-js";

declare global {
    interface Window {
        route: Route;
    }

    function route(
        name: string,
        params?: RouteParamsWithQueryOverload | RouteParam,
        absolute?: boolean,
        config?: Config
    ): string;
}

declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        $route: (
            name?: string,
            params?: RouteParamsWithQueryOverload | RouteParam,
            absolute?: boolean,
            config?: Config
        ) => Router;
    }
}

export {};
