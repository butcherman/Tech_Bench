<template>
    <div>
        <Card class="tb-card flex flex-col" title="Create New Equipment">
            <EquipmentForm
                :category-list="categoryList"
                :data-list="dataList"
                :public-tips="publicTips"
                :equipment="equipment"
            />
            <div class="flex flex-col place-items-center mt-3">
                <DeleteButton
                    class="w-3/4"
                    text="Delete Equipment"
                    @click="deleteEquipment"
                />
            </div>
        </Card>
        <div class="flex place-self-center mt-3"></div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import EquipmentForm from "@/Forms/Equipment/EquipmentForm.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    equipment: equipment;
    categoryList: categoryList[];
    dataList: string[];
    publicTips: boolean;
}>();

const deleteEquipment = () => {
    verifyModal("This action cannot be undone").then((res) => {
        if (res) {
            router.delete(route("equipment.destroy", props.equipment.equip_id));
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
