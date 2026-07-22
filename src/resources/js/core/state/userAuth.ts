import { computed, readonly } from "vue";
import { usePage } from "@inertiajs/vue3";

export const useUserAuth = () => {
    const page = usePage();

    const user = computed<User | undefined>(
        () => page.props.current_user ?? undefined,
    );
    const navBar = computed<menuItem[]>(() => page.props.navbar);

    return {
        user: user ? readonly(user) : undefined,
        navBar,
    };
};
