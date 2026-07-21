import { usePage } from "@inertiajs/vue3";
import { readonly } from "vue";

export const useUserAuth = () => {
    const { props } = usePage();

    const user: User = props.current_user;
    // const navBar = [];

    return {
        user: readonly(user),
    };
};
