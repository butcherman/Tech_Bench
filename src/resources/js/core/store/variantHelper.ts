/*
|-------------------------------------------------------------------------------
| Helper function to determine variant classes and functionality for components
|-------------------------------------------------------------------------------
*/

export const useVariantHelper = () => {
    const getVariantClass = (variant?: variantType): string => {
        switch (variant) {
            case "danger":
                return "bg-rose-600 text-white";
            case "dark":
                return "bg-gray-900 text-white";
            case "error":
                return "bg-red-500 text-white";
            case "help":
                return "bg-violet-600 text-white";
            case "info":
                return "bg-blue-400 text-white";
            case "light":
                return "bg-neutral-300";
            case "primary":
                return "bg-blue-500 text-white";
            case "secondary":
                return "bg-blue-300";
            case "success":
                return "bg-green-500 text-white";
            case "warning":
                return "bg-yellow-400";
            case "none":
                return "";
            default:
                return "bg-blue-500 text-white";
        }
    };

    return {
        getVariantClass,
    };
};
