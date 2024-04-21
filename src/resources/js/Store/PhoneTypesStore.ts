/*******************************************************************************
 * Phone Types Store holds the different types of phones (mobile, work, etc.)
 * so that this data does not need to be fetched multiple times
 *******************************************************************************/

import axios from "axios";
import { defineStore } from "pinia";
import { ref, unref } from "vue";

export const usePhoneTypesStore = defineStore("phoneTypesStore", () => {
    const phoneTypesList = ref<phoneType[]>([]);

    /**
     * If the Phone Types List is empty, fetch it from the server
     */
    const initStore = async () => {
        if (!phoneTypesList.value.length) {
            await axios.get(route("phone-types")).then((res) => {
                phoneTypesList.value = res.data;
            });
        }
    };

    const getPhoneTypes = (): phoneType[] => {
        initStore();

        return unref(phoneTypesList);
    };

    const getPhoneTypesString = (): string[] => {
        initStore();

        return phoneTypesList.value.map((item) => item.description);
    };

    return { getPhoneTypes, getPhoneTypesString };
});
