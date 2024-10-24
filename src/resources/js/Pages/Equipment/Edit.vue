<template>
    <div>
        <div class="row justify-content-center">
            <Head title="Edit Equipment" />
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Edit Equipment</div>
                        <EquipmentForm
                            :category-list="categoryList"
                            :data-list="dataList"
                            :equipment="equipment"
                            :public-tips="publicTips"
                        />
                        <div class="text-center">
                            Click the
                            <fa-icon
                                icon="circle-question"
                                class="text-muted"
                            />
                            icon for detailed information on each field.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <Link
                            :href="$route('equipment.show', equipment.equip_id)"
                            class="btn btn-info my-2"
                            >Show References</Link
                        >
                        <DeleteButton class="my-2" @click="deleteEquipment" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
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
