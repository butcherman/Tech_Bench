<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import TechTipForm from "@/Forms/TechTip/TechTipForm.vue";

defineProps<{
    permissions: techTipPermissions;
    tipTypes: tipType[];
    equipTypes: { [key: string]: customerEquipment[] }[];
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
        />
    </Card>
</template>
