type MultiFactorMethod = "email" | "authenticator";

interface PasswordPolicy {
    expire: number;
    min_length: number;
    contains_uppercase: boolean;
    contains_lowercase: boolean;
    contains_number: boolean;
    contains_special: boolean;
    disable_compromised: boolean;
}
