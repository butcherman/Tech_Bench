/*
|-------------------------------------------------------------------------------
| AppStore holds application wide variables and Layout Functions
|-------------------------------------------------------------------------------
*/

import { defineStore } from "pinia";
import { ref, computed, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { v4 as uuidv4 } from "uuid";

export const useAppStore = defineStore("appStore", () => {
    /*
    |---------------------------------------------------------------------------
    | Standard App Info
    |---------------------------------------------------------------------------
    */
    const name: string = appData.name;
    const companyName: string = appData.company_name;
    const logo: string = appData.logo;
    const version: string = appData.version;
    const copyright: string = appData.copyright;

    /*
    |---------------------------------------------------------------------------
    | Non-bagged Error Messages display on top of page, under header.
    |---------------------------------------------------------------------------
    */
    const errorList = computed<string[]>(() => {
        let errorList = usePage().props.errors;
        let nonBaggedErrors: string[] = [];

        for (let key in errorList) {
            if (typeof errorList[key] === "string") {
                nonBaggedErrors.push(errorList[key]);
            }
        }

        return nonBaggedErrors;
    });

    /*
    |---------------------------------------------------------------------------
    | Flash Data shows notifications across top of page
    |---------------------------------------------------------------------------
    */
    const flash = computed<flashData[]>(() => usePage<pageProps>().props.flash);
    const flashAlerts = ref<flashData[]>([]);

    /**
     * Manually push new message
     */
    const pushFlashMsg = (flashMsg: flashData) => {
        flashMsg.id = uuidv4();
        flashAlerts.value.push(flashMsg);
        setFlashTimeout(flashMsg.id);
    };

    /**
     * Manually remove message
     */
    const removeFlashMsg = (id: string) => {
        flashAlerts.value = flashAlerts.value.filter(
            (alert) => alert.id !== id
        );
    };

    /**
     * Auto delete message after 15 seconds
     */
    const setFlashTimeout = (id: string) => {
        setTimeout(() => {
            removeFlashMsg(id);
        }, 5000);
    };

    /**
     * Watch page flash and push alerts when changed
     */
    watch(flash, (newFlash) => {
        newFlash.forEach((newAlert) => pushFlashMsg(newAlert));
    });

    /*
    |-------------------------------------------------------------------------------
    | Return State Variables
    |-------------------------------------------------------------------------------
    */
    return {
        name,
        companyName,
        logo,
        version,
        copyright,
        errorList,

        flashAlerts,
        pushFlashMsg,
        removeFlashMsg,
    };
});
