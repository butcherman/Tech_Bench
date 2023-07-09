<template>
    <div>
        <Head title="Update Equipment" />
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <EquipmentForm
                            :categories="categories"
                            :data-list="dataList"
                            :equipment="equipment"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-info w-75 my-2">
                            <fa-icon icon="eye" />
                            Show References
                        </button>
                        <DeleteButton class="w-75 my-2" @click="deleteEquipment" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import EquipmentForm from "@/Forms/Equipment/EquipmentForm.vue";
import DeleteButton from '@/Components/_Base/Buttons/DeleteButton.vue';
import verify from "@/Modules/verify";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    categories: categoryList[];
    dataList: string[];
    equipment: equipWithData;
}>();

const deleteEquipment = () => {
    verify().then((res) => {
        if(res) {
            console.log('do it');

            router.delete(route('equipment.destroy', props.equipment.equip_id));
        }
    })
}
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
