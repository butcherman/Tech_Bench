import type { Link } from '@inertiajs/inertia-vue3';
import type { Route, RouteParam, RouteParamsWithQueryOverload, Config } from 'ziggy-js';

export {};

declare global {
    interface Window {
        route: Route;
    }

    function route(
        name     : string,
        params  ?: RouteParamsWithQueryOverload | RouteParam,
        absolute?: boolean,
        config  ?: Config,
    ): string;
}

export * from './LayoutTypes';
export * from './UserDataTypes';
export * from './FormTypes';
export * from './InputTypes';
export * from './LogDataTypes';
