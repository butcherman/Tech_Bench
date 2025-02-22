/*
|-------------------------------------------------------------------------------
| AppStore holds application wide variables and Layout Functions
|-------------------------------------------------------------------------------
*/

import { defineStore } from "pinia";

export const useAppStore = defineStore("appStore", () => {
    /*
    |---------------------------------------------------------------------------
    | Standard App Info
    |---------------------------------------------------------------------------
    */
    const name = appData.name;
    const companyName = appData.company_name;
    const logo = appData.logo;
    const version = appData.version;
    const copyright = appData.copyright;

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
    };
});
