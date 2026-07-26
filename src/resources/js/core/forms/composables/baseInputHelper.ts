import { useField } from "vee-validate";
import { readonly, Ref, ref, toRef } from "vue";

export const useBaseInputHelper = (props: InputBaseProps, emit: any) => {
    // TODO - type the emit variable
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
    | Vee-Validate
    |-------------------------------------------------------------------------------
    */
    const nameRef = toRef(props, "name");
    const {
        errorMessage,
        value,
    }: {
        errorMessage: Ref<string | undefined, string | undefined>;
        value: Ref<any | any[]>;
    } = useField(nameRef);

    return {
        hasFocus: readonly(hasFocus),
        onFocus,
        onBlur,
        errorMessage,
        value,
    };
};
