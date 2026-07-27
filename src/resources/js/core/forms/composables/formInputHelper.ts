export const useFormInputHelper = (props: InputBaseProps) => {
    const defaultVariant = "outlined";

    /**
     * Variant styling for standard inputs
     */
    const inputVariantStyle = {
        filled: "form-input-filled",
        standard: "form-input-standard",
        outlined: "form-input-outlined",
    }[props.variant ?? defaultVariant];

    const prependVariantStyle = {
        filled: "form-prepend-filled",
        standard: "form-prepend-standard",
        outlined: "form-prepend-outlined",
    }[props.variant ?? defaultVariant];

    const appendVariantStyle = {
        filled: "form-append-filled",
        standard: "form-append-standard",
        outlined: "form-append-outlined",
    }[props.variant ?? defaultVariant];

    /**
     * Variant styling for switch inputs
     */
    const switchSize = {
        large: "min-w-15 w-15 h-8",
        normal: "min-w-10 w-10 h-6",
        small: "min-w-8 w-8 h-4",
    }[props.size ?? "normal"];

    const switchInputSize = {
        large: "w-6 h-6 peer-checked:translate-x-7",
        normal: "w-4 h-4 peer-checked:translate-x-4",
        small: "w-3 h-3 top-1.5 peer-checked:translate-x-3",
    }[props.size ?? "normal"];

    return {
        inputVariantStyle,
        prependVariantStyle,
        appendVariantStyle,
        switchSize,
        switchInputSize,
    };
};
