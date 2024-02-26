<template>
    <div class="row justify-content-center">
        <Head title="Create New Equipment" />
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Create New Equipment</div>
                    <EquipmentForm
                        :category-list="categoryList"
                        :data-list="dataList"
                        :equipment="equipment"
                    />
                    <div class="text-center">
                        Click the
                        <fa-icon icon="circle-question" class="text-muted" />
                        icon for detailed information on each field.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import EquipmentForm from "@/Forms/Equipment/EquipmentForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    equipment: equipment;
    categoryList: categoryList[];
    dataList: string[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
