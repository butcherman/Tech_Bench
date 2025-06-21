import { usePage } from "@inertiajs/vue3";
import { defineStore } from "pinia";
import { computed } from "vue";

export const useAuthStore = defineStore("authStore", () => {
    const user = computed<user>(() => usePage<pageProps>().props.current_user);
    const navbar = computed<navbarItem[]>(
        () => usePage<pageProps>().props.navbar
    );

    return {
        user,
        navbar,
    };
});
