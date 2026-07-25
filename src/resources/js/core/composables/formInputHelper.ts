import { computed } from "vue";

interface InputCommonProps {
    inputStyle?: "filled" | "standard" | "outlined";
}

export const useFormInputHelper = (props: InputCommonProps) => {
    /**
     * Variant styling for the input
     */
    const inputStyleClass = computed<string>(() => {
        switch (props.inputStyle) {
            case "standard":
                return "form-input-standard";
            case "filled":
                return "form-input-filled";
            default:
                return "form-input-outlined";
        }
    });

    const prependStyleClass = computed<string>(() => {
        switch (props.inputStyle) {
            case "standard":
                return "form-prepend-standard";
            case "filled":
                return "form-prepend-filled";
            default:
                return "form-prepend-outlined";
        }
    });

    const appendStyleClass = computed<string>(() => {
        switch (props.inputStyle) {
            case "standard":
                return "form-append-standard";
            case "filled":
                return "form-append-filled";
            default:
                return "form-append-outlined";
        }
    });

    return {
        inputStyleClass,
        prependStyleClass,
        appendStyleClass,
    };
};
