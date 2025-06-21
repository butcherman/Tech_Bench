<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import TechTipForm from "@/Forms/TechTip/TechTipForm.vue";

defineProps<{
    permissions: techTipPermissions;
    tipTypes: tipType[];
    equipTypes: { [key: string]: customerEquipment[] }[];
    techTip: techTip;
    equipList: number[];
    fileList: fileUpload[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <Card>
        <TechTipForm
            :tip-types="tipTypes"
            :equip-types="equipTypes"
            :allow-public="permissions.public"
            :tech-tip="techTip"
            :equip-list="equipList"
            :file-list="fileList"
        />
    </Card>
</template>
