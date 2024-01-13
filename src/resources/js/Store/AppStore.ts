/**
 * AppState holds application wide variables
 * These variables do not have setters as they are only set by the server
 */

import { defineStore } from "pinia";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export const useAppStore = defineStore("appStore", () => {
    const name = computed<string>(() => usePage<pageProps>().props.app.name);
    const logo = computed<string>(() => usePage<pageProps>().props.app.logo);
    const navbar = computed<navbar[]>(() => usePage<pageProps>().props.navbar);
    const flash = computed<flashData[]>(() => usePage<pageProps>().props.flash);
    const user = computed<user | null>(
        () => usePage<pageProps>().props.current_user
    );
    const userNotifications = computed<userNotificationProp>(
        () => usePage<pageProps>().props.user_notifications
    );

    return { name, logo, flash, user, userNotifications, navbar };
});
