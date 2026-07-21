import { usePage } from "@inertiajs/vue3";
import { readonly } from "vue";

const { props } = usePage();

export const useUserAuth = () => {
    // const user: User = props.current_user;
    // const navBar = [];
    const user = "test user";

    console.log(props);

    return {
        user: readonly(user),
    };
};
