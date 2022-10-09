import type { Route } from 'ziggy-js';

export {};

declare global {
    interface Window {
        route: Route;
    }
}

export * from './LayoutTypes';
