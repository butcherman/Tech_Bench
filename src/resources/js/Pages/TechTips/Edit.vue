<template>
    <div>
        <Head title="Edit Tech Tip" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <TechTipForm
                            :tech-tip="tipData"
                            :tip-types="tipTypes"
                            :equip-list="equipStore.getMultiSelectBox()"
                            :allow-public="permissions.public"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TechTipForm from "@/Forms/TechTips/TechTipForm.vue";
import { useEquipmentStore } from "@/Store/EquipmentStore";

defineProps<{
    tipTypes: tipType[];
    tipData: techTip;
    permissions: techTipPermissions;
}>();

const equipStore = useEquipmentStore();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
