export const getStatusType = (type: string) => {
    switch (type) {
        case "status":
            return "info";
        case "warning":
            return "warn";
        default:
            return type;
    }
};

export const getStatusIcon = (type: string) => {
    switch (type) {
        case "success":
            return "circle-check";
        case "warning":
            return "triangle-exclamation";
        case "danger":
            return "exclamation-circle";
        default:
            return "circle-info";
    }
};
