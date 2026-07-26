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

type ArrayProperty<T, TElement> = {
    [K in keyof T]: T[K] extends readonly TElement[] ? K : never;
}[keyof T];
