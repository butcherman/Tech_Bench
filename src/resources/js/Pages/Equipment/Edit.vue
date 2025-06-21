<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import EquipmentForm from "@/Forms/Equipment/EquipmentForm.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    equipment: equipment;
    categoryList: equipmentCategory[];
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

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Edit Equipment">
            <div>
                <EquipmentForm
                    :category-list="categoryList"
                    :data-list="dataList"
                    :public-tips="publicTips"
                    :equipment="equipment"
                />
                <div class="flex justify-center my-3">
                    <DeleteButton
                        class="w-3/4"
                        text="Delete Equipment"
                        @click="deleteEquipment"
                    />
                </div>
            </div>
        </Card>
    </div>
</template>
