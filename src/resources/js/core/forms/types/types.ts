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
