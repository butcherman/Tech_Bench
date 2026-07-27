type InputVariant = "filled" | "standard" | "outlined";

interface InputBaseProps {
    name: string;
    label?: string;
    helpMessage?: string;
    variant?: InputVariant;
    placeholder?: string;
    helpVisible?: boolean;
    autocomplete?: string;
    disabled?: boolean;
    switchVariant?: variantType;
    size?: componentSize;
}

interface InputSelectProps<
    TGroup extends Record<string, unknown>,
    TOption extends string | Record<string, unknown>,
> extends InputBaseProps {
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
    groupTextField?: keyof TGroup;
    groupListField?: ArrayProperty<TGroup, TOption>;
}

type ArrayProperty<T, TElement> = {
    [K in keyof T]: T[K] extends readonly TElement[] ? K : never;
}[keyof T];
