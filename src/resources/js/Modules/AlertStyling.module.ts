/**
 * Icon that will show next to message
 */
export const getAlertIcon = (type: string) => {
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
