/**
 * AppState holds application wide variables
 */

import { defineStore } from "pinia";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export const useAppStore = defineStore("appStore", () => {
    const name = computed<string>(() => usePage<pageProps>().props.app.name);
    const logo = computed<string>(() => usePage<pageProps>().props.app.logo);

    return { name, logo };
});
