import { computed, readonly } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const user = computed<User | undefined>(
    () => page.props.current_user ?? undefined,
);
const navBar = computed<menuItem[]>(() => page.props.navbar);

export const useUserAuth = () => {
    return {
        user: user ? readonly(user) : undefined,
        navBar,
    };
};
