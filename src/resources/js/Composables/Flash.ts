export const getFlashColor = (flashType: string): string => {
    return flashType === "status" ? "info" : flashType;
};

export const getFlashIcon = (flashType: string): string => {
    return flashType === "status" ? "$info" : "$" + flashType;
};
