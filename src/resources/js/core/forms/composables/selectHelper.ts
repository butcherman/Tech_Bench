export const useSelectHelper = <
    TOption extends string | Record<string, unknown>,
    TGroup extends Record<string, unknown>,
>(
    props: InputSelectProps<TGroup, TOption>,
) => {
    /**
     * Get the value of the list item
     */
    const getValue = (opt: TOption) => {
        if (typeof opt === "string") {
            return opt;
        }

        return props.valueField ? opt[props.valueField] : opt;
    };

    /**
     * Get the text to be displayed
     */
    const getOptionText = (item: TOption): string => {
        if (typeof item === "string") {
            return item;
        }

        return props.textField ? String(item[props.textField]) : String(item);
    };

    /**
     * Get the label for the Option Group
     */
    const getGroupText = (group: TGroup): string => {
        if (props.groupTextField) {
            return String(group[props.groupTextField]);
        }

        return "text";
    };

    /**
     * Get the list of grouped items for the Option Group
     */
    const getGroupItems = (group: TGroup): readonly TOption[] => {
        if (props.groupListField) {
            return group[props.groupListField] as readonly TOption[];
        }

        return [];
    };

    return {
        getValue,
        getOptionText,
        getGroupText,
        getGroupItems,
    };
};
