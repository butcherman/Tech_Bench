<template>
    <div>
        <Head title="Create New Equipment" />
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <EquipmentForm :categories="categories" :data-list="dataList" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import EquipmentForm from '@/Forms/Equipment/EquipmentForm.vue';

defineProps<{
    categories: categoryList[];
    dataList: string[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>
