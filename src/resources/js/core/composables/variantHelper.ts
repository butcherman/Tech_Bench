/*
|-------------------------------------------------------------------------------
| Helper function to determine variant classes and functionality for components
|-------------------------------------------------------------------------------
*/

export const useVariantHelper = () => {
    /**
     * Get the base color of the variant
     */
    const getVariantBase = (variant: VariantType): string => {
        return {
            danger: "rose-600",
            dark: "gray-800",
            error: "rose-500",
            help: "violet-600",
            info: "blue-400",
            light: "neutral-300",
            primary: "blue-600",
            secondary: "blue-300",
            success: "emerald-500",
            warning: "amber-500",
        }[variant];
    };

    /**
     * Get the active color of the variant
     */
    // const getActiveVariant = (variant: VariantType): string => {
    //     return {
    //         danger: "rose-700",
    //         dark: "gray-900",
    //         error: "rose-600",
    //         help: "violet-700",
    //         info: "blue-400",
    //         light: "neutral-400",
    //         primary: "blue-700",
    //         secondary: "blue-400",
    //         success: "emerald-600",
    //         warning: "amber-600",
    //     }[variant];
    // };

    /**
     * Basic background color per variant
     */
    // const getBackgroundClass = (variant: VariantType): string => {
    //     return {
    //         danger: "bg-rose-600",
    //         dark: "bg-gray-900",
    //         error: "bg-red-500",
    //         help: "bg-violet-600",
    //         info: "bg-blue-400",
    //         light: "bg-neutral-300",
    //         primary: "bg-blue-500",
    //         secondary: "bg-blue-300",
    //         success: "bg-green-500",
    //         warning: "bg-yellow-400",
    //         none: "",
    //     }[variant];
    // };

    /**
     * Class information for background and text colors based on variant level
     */
    const getVariantClass = (variant: VariantType): string => {
        return {
            danger: `bg-rose-600 hover:bg-rose-700 text-white focus:outline-rose-700`,
            dark: `bg-slate-800 hover:bg-slate-900 text-white focus:outline-slate-900`,
            error: `bg-rose-500 hover:bg-rose-600 text-white focus:outline-rose-600`,
            help: `bg-violet-600 hover:bg-violet-700 text-white focus:outline-violet-700`,
            info: `bg-blue-400 hover:bg-blue-500 text-white focus:outline-blue-500`,
            light: `bg-neutral-300 hover:bg-neutral-400 text-slate-800 focus:outline-neutral-400`,
            primary: `bg-blue-600 hover:bg-blue-700 text-white focus:outline-blue-700`,
            secondary: `bg-blue-300 hover:bg-blue-400 text-slate-800 focus:outline-blue-400`,
            success: `bg-emerald-500 hover:bg-emerald-600 text-white focus:outline-emerald-600`,
            warning: `bg-amber-500 hover:bg-amber-600 text-white focus:outline-amber-600`,
        }[variant];
    };

    /**
     * Class information for light backgrounds with darker matching text
     */
    const getLightVariantClass = (variant: VariantType): string => {
        return {
            danger: `bg-rose-100 hover:bg-rose-300 text-rose-700 `,
            dark: `bg-slate-100 hover:bg-slate-300 text-slate-900 focus:outline-slate-900`,
            error: `bg-rose-100 hover:bg-rose-300 text-rose-600 focus:outline-rose-600`,
            help: `bg-violet-100 hover:bg-violet-300 text-violet-700 focus:outline-violet-700`,
            info: `bg-blue-100 hover:bg-blue-300 text-blue-500 focus:outline-blue-500`,
            light: `bg-neutral-100 hover:bg-neutral-300 text-slate-600 focus:outline-neutral-400`,
            primary: `bg-blue-300 hover:bg-blue-500 text-blue-700 focus:outline-blue-700`,
            secondary: `bg-blue-100 hover:bg-blue-300 text-blue-400 focus:outline-blue-400`,
            success: `bg-emerald-100 hover:bg-emerald-300 text-emerald-600 focus:outline-emerald-600`,
            warning: `bg-amber-100 hover:bg-amber-300 text-amber-600 focus:outline-amber-600`,
        }[variant];
    };

    /**
     * Class information for background and text colors when component is 'active'
     */
    const getActiveVariantClass = (variant: VariantType): string => {
        return {
            danger: "rose-700",
            dark: "gray-900",
            error: "rose-600",
            help: "violet-700",
            info: "blue-500",
            light: "neutral-400",
            primary: "blue-700",
            secondary: "blue-400",
            success: "emerald-600",
            warning: "amber-600",
        }[variant];
    };

    /**
     * Font-Awesome Icon based on Variant Icon for alerts
     */
    const getVariantIcon = (variant: VariantType): string => {
        return {
            danger: "exclamation-circle",
            dark: "bell",
            error: "bug",
            help: "circle-question",
            info: "bell",
            light: "bell",
            primary: "bell",
            secondary: "bell",
            success: "circle-check",
            warning: "triangle-exclamation",
            none: "",
        }[variant];
    };

    return {
        // getBackgroundClass,
        getVariantClass,
        getLightVariantClass,
        getActiveVariantClass,
        getVariantBase,
        getVariantIcon,
    };
};
