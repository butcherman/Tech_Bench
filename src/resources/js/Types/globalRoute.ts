import { route as routeFn } from "ziggy-js";
import type Echo from "laravel-echo";

declare global {
    var route: typeof routeFn;
    var Pusher: any;
    var Echo: Echo<any>;
}

declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        $route: typeof routeFn;
    }
}
