import { readonly, ref } from "vue";

export const useInputHelper = (props: InputBaseProps, emit: any) => {
    // TODO - type emit
    /*
    |---------------------------------------------------------------------------
    | Input Focus State
    |---------------------------------------------------------------------------
    */
    const hasFocus = ref<boolean>(false);

    const onFocus = (): void => {
        hasFocus.value = true;
        emit("focus");
    };

    const onBlur = (): void => {
        hasFocus.value = false;
        emit("blur");
    };

    /*
    |-------------------------------------------------------------------------------
    | Input Styling
    |-------------------------------------------------------------------------------
    */
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
        lg: "min-w-15 w-15 h-8",
        md: "min-w-10 w-10 h-6",
        sm: "min-w-8 w-8 h-4",
    }[props.size ?? "md"];

    const switchInputSize = {
        lg: "w-6 h-6 peer-checked:translate-x-7",
        md: "w-4 h-4 peer-checked:translate-x-4",
        sm: "w-3 h-3 top-1.5 peer-checked:translate-x-3",
    }[props.size ?? "md"];

    return {
        hasFocus: readonly(hasFocus),
        onFocus,
        onBlur,
        inputVariantStyle,
        prependVariantStyle,
        appendVariantStyle,
        switchInputSize,
        switchSize,
    };
};
