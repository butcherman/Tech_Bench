<template>
    <thead>
        <tr>
            <th>
                Customer Name
                <span class="pointer float-end mt-2" title="Sort" v-tooltip>
                    <fa-icon :icon="getNameSort" @click="updateSort('name')" />
                </span>
            </th>
            <th>
                City
                <span class="pointer float-end mt-2" title="Sort" v-tooltip>
                    <fa-icon :icon="getCitySort" @click="updateSort('city')" />
                </span>
            </th>
            <th>Equipment</th>
        </tr>
        <tr>
            <td>
                <input
                    id="cust-name"
                    v-model="searchParam.name"
                    type="search"
                    class="form-control"
                    placeholder="Search by Customer Name or ID"
                    @change="triggerSearch"
                />
            </td>
            <td>
                <input
                    id="cust-city"
                    v-model="searchParam.city"
                    type="search"
                    class="form-control"
                    placeholder="Search by City"
                    @change="triggerSearch"
                />
            </td>
            <td>
                <div class="input-group flex-nowrap">
                    <select
                        id="cust-equip"
                        v-model="searchParam.equip"
                        class="form-select"
                        @change="triggerSearch"
                    >
                        <option value="null" disabled selected hidden>
                            Search by Equipment
                        </option>
                        <optgroup
                            v-for="(equipList, cat) in equipment"
                            :key="cat"
                            :label="cat.toString()"
                        >
                            <option v-for="equip in equipList">
                                {{ equip }}
                            </option>
                        </optgroup>
                    </select>
                    <span
                        class="input-group-text pointer"
                        title="Search"
                        v-tooltip
                        @click="triggerSearch"
                    >
                        <fa-icon icon="fa-brands fa-searchengin" />
                    </span>
                    <span
                        class="input-group-text pointer"
                        title="Clear Search"
                        v-tooltip
                        @click="resetSearch"
                    >
                        <fa-icon icon="fa-xmark" />
                    </span>
                </div>
            </td>
        </tr>
    </thead>
</template>

<script setup lang="ts">
import { reactive, computed } from "vue";
import {
    triggerSearch,
    resetSearch,
    searchParam,
} from "@/State/Customer/SearchState";

defineProps<{
    equipment: { [key: string]: string[] };
}>();

const sort = reactive({
    name: "none",
    city: "none",
});

const getNameSort = computed(() => {
    switch (sort.name) {
        case "up":
            return "sort-up";
        case "down":
            return "sort-down";
        default:
            return "sort";
    }
});

const getCitySort = computed(() => {
    switch (sort.city) {
        case "up":
            return "sort-up";
        case "down":
            return "sort-down";
        default:
            return "sort";
    }
});

/**
 * Modify the sort parameters and perform a new search
 */
const updateSort = (field: string) => {
    if (sort[field as keyof typeof sort] === "down") {
        sort[field as keyof typeof sort] = "up";
        searchParam.sortType = "desc";
    } else {
        sort[field as keyof typeof sort] = "down";
        searchParam.sortType = "asc";
    }

    //  Remove sort from other fields
    if (field === "name") {
        sort.city = "none";
    } else {
        sort.name = "none";
    }

    searchParam.sortField = field;
    triggerSearch();
};
</script>
