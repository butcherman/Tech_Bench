<template>
    <div>
        <Head title="Create New Equipment" />
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <EquipmentForm :categories="categories" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import EquipmentForm from '@/Forms/Equipment/EquipmentForm.vue';
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    categories: categoryList[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>
