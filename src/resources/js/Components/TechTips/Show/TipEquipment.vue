<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";

defineProps<{
    equipmentList: equipment[];
}>();
</script>

<template>
    <div class="text-muted">
        <div>For Equipment:</div>
        <div>
            <BaseBadge
                v-for="equip in equipmentList"
                :text="equip.name"
                :key="equip.equip_id"
                class="m-1"
            />
        </div>
    </div>
</template>
