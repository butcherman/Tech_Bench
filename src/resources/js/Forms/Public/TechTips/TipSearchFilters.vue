<template>
    <div>
        <h6 class="mt-2 mb-0">Equipment Type:</h6>
        <template v-for="category in equipTypes" :key="category.cat_id">
            <template v-if="category.equipment_type.length">
                <p class="mt-2 mb-0">{{ category.name }}</p>
                <div
                    v-for="equip in category.equipment_type"
                    :key="equip.equip_id"
                    class="form-check form-switch"
                >
                    <input
                        :id="`equip-id-${equip.equip_id}`"
                        class="form-check-input"
                        type="checkbox"
                        :value="equip.equip_id"
                        name="articleType"
                        v-model="searchParams.equipList"
                        @change="triggerSearch"
                    />
                    <label
                        :for="`equip-id-${equip.equip_id}`"
                        class="form-check-label"
                    >
                        {{ equip.name }}
                    </label>
                </div>
            </template>
        </template>
    </div>
</template>

<script setup lang="ts">
import { searchParams, triggerSearch } from "@/Modules/TipSearch.module";

defineProps<{
    equipTypes: categoryList[];
}>();
</script>
