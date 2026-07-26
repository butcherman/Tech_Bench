export const useFormInputHelper = (props: InputBaseProps) => {
    const defaultVariant = "outlined";

    /**
     * Variant styling for the input
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

    return {
        inputVariantStyle,
        prependVariantStyle,
        appendVariantStyle,
    };
};
