<script setup lang="ts">
import {
    searchParams,
    triggerPublicSearch,
    triggerSearch,
} from "@/Composables/TechTip/TipSearch.module";

const props = defineProps<{
    filterData: {
        tip_types: tipType[];
        equip_types: equipmentCategory[];
    };
    isPublic?: boolean;
}>();

/**
 * Determine if we are performing a public or private search.
 */
const search = () => {
    if (props.isPublic) {
        triggerPublicSearch();

        return;
    }

    triggerSearch();
};
</script>

<template>
    <div class="overflow-hidden">
        <template v-if="filterData.tip_types.length">
            <h6>Article Type:</h6>
            <div>
                <div
                    v-for="type in filterData.tip_types"
                    :key="type.tip_type_id"
                    class="ms-4"
                >
                    <label class="inline-flex items-center cursor-pointer">
                        <input
                            v-model="searchParams.typeList"
                            :name="type.description"
                            :value="type.tip_type_id"
                            class="sr-only peer"
                            type="checkbox"
                            @change="search"
                        />
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-hidden peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:rtl:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"
                        />
                        <span
                            class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                        >
                            {{ type.description }}
                        </span>
                    </label>
                </div>
            </div>
        </template>
        <h6>Equipment Type:</h6>
        <div class="ms-2">
            <div v-for="cat in filterData.equip_types" :key="cat.cat_id">
                <template v-if="cat.equipment_type.length">
                    <p>{{ cat.name }}</p>
                    <div
                        v-for="equip in cat.equipment_type"
                        :key="equip.equip_id"
                        class="ms-2"
                    >
                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                v-model="searchParams.equipList"
                                :name="equip.name"
                                :value="equip.equip_id"
                                class="sr-only peer"
                                type="checkbox"
                                @change="search"
                            />
                            <div
                                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-hidden peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:rtl:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"
                            />
                            <span
                                class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                                {{ equip.name }}
                            </span>
                        </label>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
