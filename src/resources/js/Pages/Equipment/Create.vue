<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import EquipmentForm from "@/Forms/Equipment/EquipmentForm.vue";

defineProps<{
    categoryList: equipmentCategory[];
    dataList: string[];
    publicTips: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Create New Equipment">
            <EquipmentForm
                :category-list="categoryList"
                :data-list="dataList"
                :public-tips="publicTips"
            />
        </Card>
    </div>
</template>
