import { readonly } from "vue";
import { usePage } from "@inertiajs/vue3";

export const useUserAuth = () => {
    const { props } = usePage();

    const user: User | undefined = props.current_user ?? undefined;
    const navBar = props.navbar;

    return {
        user: user ? readonly(user) : undefined,
        navBar,
    };
};
