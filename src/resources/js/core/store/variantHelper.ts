/*
|-------------------------------------------------------------------------------
| Helper function to determine variant classes and functionality for components
|-------------------------------------------------------------------------------
*/

export const useVariantHelper = () => {
    /**
     * Basic background color per variant
     */
    const getBackgroundClass = (variant: variantType): string => {
        switch (variant) {
            case "danger":
                return "bg-rose-600";
            case "dark":
                return "bg-gray-900";
            case "error":
                return "bg-red-500";
            case "help":
                return "bg-violet-600";
            case "info":
                return "bg-blue-400";
            case "light":
                return "bg-neutral-300";
            case "primary":
                return "bg-blue-500";
            case "secondary":
                return "bg-blue-300";
            case "success":
                return "bg-green-500";
            case "warning":
                return "bg-yellow-400";
            case "none":
                return "";
            default:
                return "bg-blue-500";
        }
    };

    /**
     * Class information for buttons and other components
     */
    const getVariantClass = (variant?: variantType): string => {
        switch (variant) {
            case "danger":
                return "bg-rose-600 text-white focus:outline-rose-700";
            case "dark":
                return "bg-gray-900 text-white focus:outline-gray-900";
            case "error":
                return "bg-red-500 text-white focus:outline-red-860";
            case "help":
                return "bg-violet-600 text-white focus:outline-violet-700";
            case "info":
                return "bg-blue-400 text-white focus:outline-blue-500";
            case "light":
                return "bg-neutral-300 focus:outline-nuetral-400";
            case "primary":
                return "bg-blue-500 text-white focus:outline-blue-600";
            case "secondary":
                return "bg-blue-300 focus:outline-blue-400";
            case "success":
                return "bg-green-500 text-white focus:outline-green-600";
            case "warning":
                return "bg-yellow-400 focus:outline-yellow-500";
            case "none":
                return "";
            default:
                return "bg-blue-500 text-white focus:outline-blue-600";
        }
    };

    return {
        getBackgroundClass,
        getVariantClass,
    };
};
