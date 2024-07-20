/*******************************************************************************
 * Equipment Store holds Equipment Data so that it does not need to be fetched
 * multiple times
 *******************************************************************************/

import axios from "axios";
import { defineStore } from "pinia";
import { ref, unref } from "vue";

export const useEquipmentStore = defineStore("equipmentStore", () => {
    const equipmentList = ref<categoryList[]>([]);

    /**
     * If the Equipment List is empty, fetch it from the server
     */
    const initStore = async () => {
        if (!equipmentList.value.length) {
            await axios.get(route("equipment-list")).then((res) => {
                equipmentList.value = res.data;
            });
        }
    };

    /**
     * Return the Unmodified Equipment List to the user
     */
    const getEquipmentList = () => {
        initStore();

        return unref(equipmentList);
    };

    /**
     * Modify the Equipment List for a select box
     */
    const getSelectBox = () => {
        initStore();

        let modifiedList: {
            [key: string]: { text: string; value: string | number }[];
        } = {};

        equipmentList.value.forEach((category: categoryList) => {
            let equipList: { text: string; value: string | number }[] = [];

            category.equipment_type.forEach((equip: equipment) => {
                equipList.push({
                    text: equip.name,
                    value: equip.equip_id,
                });
            });

            modifiedList[category.name] = equipList;
        });

        return modifiedList;
    };

    /**
     * Modify the Equipment List for a Multi-Select Box
     */
    const getMultiSelectBox = () => {
        initStore();

        let modifiedList: {
            label: string;
            options: { text: string; value: string | number }[];
        }[] = [];

        equipmentList.value.forEach((category: categoryList) => {
            let equipList: { text: string; value: string | number }[] = [];

            category.equipment_type.forEach((equip: equipment) => {
                equipList.push({
                    text: equip.name,
                    value: equip.equip_id,
                });
            });

            modifiedList.push({
                label: category.name,
                options: equipList,
            });
        });

        return modifiedList;
    };

    return {
        getEquipmentList,
        getSelectBox,
        getMultiSelectBox,
    };
});
