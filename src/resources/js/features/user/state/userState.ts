import { computed, readonly } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const authorizedUser = computed<User | undefined>(
    () => page.props.current_user ?? undefined,
);
const navBar = computed<MenuItem[]>(() => page.props.navbar);

export const useUserState = () => {
    return {
        authorizedUser: authorizedUser ? readonly(authorizedUser) : undefined,
        navBar,
    };
};
