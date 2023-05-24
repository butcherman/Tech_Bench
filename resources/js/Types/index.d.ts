import type {
    Route,
    RouteParam,
    RouteParamsWithQueryOverload,
    Config,
} from "ziggy-js";

export {};

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

export type voidFunctionType = () => void;

// export * from './LayoutTypes';
// export * from './UserDataTypes';
// export * from './FormTypes';
// export * from './InputTypes';
// export * from './LogDataTypes';
// export * from './EquipmentTypes';
// export * from './CustomerTypes';
// export * from './TechTipTypes';
