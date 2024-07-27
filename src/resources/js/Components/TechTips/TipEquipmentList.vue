<template>
    <div class="border-bottom border-secondary-subtle pb-2 tip-equipment">
        <div>For Equipment:</div>
        <span v-for="equip in tipEquipment" :key="equip.equip_id">
            <span class="badge text-bg-primary">
                {{ equip.name }}
            </span>
        </span>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    tipEquipment: equipment[];
}>();
</script>

<style scoped lang="scss">
.tip-equipment {
    color: #999999;
    div {
        font-weight: bold;
    }
    span {
        padding: 3px 5px;
        border-right: 1px solid #999999;
        &:last-child {
            border-right: none;
        }
    }
}
</style>
