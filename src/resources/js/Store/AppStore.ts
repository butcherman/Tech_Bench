/*******************************************************************************
 * AppStore holds application wide variables and Layout Functions
 *******************************************************************************/

import { defineStore } from "pinia";
import { ref, computed, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { v4 as uuidv4 } from "uuid";

export const useAppStore = defineStore("appStore", () => {
    /***************************************************************************
     * Standard App Info
     ***************************************************************************/
    const name = computed<string>(() => usePage<pageProps>().props.app.name);
    const companyName = computed<string>(
        () => usePage<pageProps>().props.app.company_name
    );
    const logo = computed<string>(() => usePage<pageProps>().props.app.logo);
    const version = computed<string>(
        () => usePage<pageProps>().props.app.version
    );
    const copyright = computed<string>(
        () => usePage<pageProps>().props.app.copyright
    );

    /***************************************************************************
     * Dynamic Navbar for authenticated users
     ***************************************************************************/
    const navbar = computed<navbar[]>(() => usePage<pageProps>().props.navbar);

    /***************************************************************************
     * User Data
     ***************************************************************************/
    const user = computed<user | null>(
        () => usePage<pageProps>().props.current_user
    );

    /***************************************************************************
     * User Session Data
     ***************************************************************************/
    const idleTimeout = computed<number>(
        () => usePage<pageProps>().props.idle_timeout
    );

    /***************************************************************************
     * Flash Data shows notifications across top of page
     ***************************************************************************/
    const flash = computed<flashData[]>(() => usePage<pageProps>().props.flash);
    const flashAlerts = ref<flashData[]>([]);

    // Manually push new message
    const pushFlashMsg = (flashMsg: flashData) => {
        flashMsg.id = uuidv4();
        flashAlerts.value.push(flashMsg);
        setFlashTimeout(flashMsg.id);
    };
    // Manually remove message
    const removeFlashMsg = (id: string) => {
        flashAlerts.value = flashAlerts.value.filter(
            (alert) => alert.id !== id
        );
    };
    // Auto delete message after 15 seconds
    const setFlashTimeout = (id: string) => {
        setTimeout(() => {
            removeFlashMsg(id);
        }, 15000);
    };
    // Watch page flash and push alerts when changed
    watch(flash, (newFlash) => {
        newFlash.forEach((newAlert) => pushFlashMsg(newAlert));
    });

    /***************************************************************************/
    return {
        name,
        companyName,
        logo,
        user,
        idleTimeout,
        navbar,
        version,
        copyright,

        flash,
        flashAlerts,
        pushFlashMsg,
        removeFlashMsg,
    };
});
