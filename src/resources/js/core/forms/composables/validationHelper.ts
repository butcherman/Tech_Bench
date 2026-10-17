import { useField } from "vee-validate";
import type { Ref } from "vue";

export const useValidationHelper = <TValue>(nameRef: Ref<string>) => {
    const { errorMessage, value } = useField<TValue>(nameRef);

    return {
        errorMessage,
        value,
    };
};
