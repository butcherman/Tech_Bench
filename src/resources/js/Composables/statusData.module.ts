export const getStatusType = (type: string) => {
    switch (type) {
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
        default:
            return "bg-blue-500 text-white";
    }
};

export const getStatusIcon = (type: string) => {
    switch (type) {
        case "danger":
            return "exclamation-circle";
        case "error":
            return "bug";
        case "help":
            return "circle-question";
        case "success":
            return "circle-check";
        case "warning":
            return "triangle-exclamation";
        default:
            return "circle-info";
    }
};
