import routeFn from "ziggy-js";

declare global {
    var route: typeof routeFn;
}

declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        $route: typeof routeFn;
    }
}
