export const useTableStyles = () => {
    /**
     * Get the Sort Icon for each column
     */
    const getSortingIcon = (
        col: false | "asc" | "desc",
    ): "sort-down" | "sort-up" | "sort" => {
        switch (col) {
            case "asc":
                return "sort-down";
            case "desc":
                return "sort-up";
            default:
                return "sort";
        }
    };

    return {
        getSortingIcon,
    };
};
