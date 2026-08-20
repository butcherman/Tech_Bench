import { email, authenticator } from "@/wayfinder/routes/two-factor/setup";

export const useTwoFactorMethodHelper = () => {
    const getMethodSetupText = (method: MultiFactorMethod) => {
        return {
            email: "Use Email Verification Code",
            authenticator: "Use Authenticator App",
        }[method];
    };

    const getMethodSetupUrl = (method: MultiFactorMethod) => {
        return {
            email: email.url(),
            authenticator: authenticator.url(),
        }[method];
    };

    return {
        getMethodSetupText,
        getMethodSetupUrl,
    };
};
