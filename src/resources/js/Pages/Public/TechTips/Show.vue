<template>
    <div>
        <Link :href="$route('publicTips.index')" class="btn btn-info">
            <fa-icon icon="left-long" />
            Back to Search
        </Link>
        <div class="border-bottom border-secondary-subtle pb-2">
            <TipDetailsTitle :tip-data="tipData" public-shown />
        </div>
        <TipEquipmentList :tip-equipment="tipEquipment" />
        <TipDetails :tip-data="tipData" class="mt-4" />
        <TipFiles
            v-if="tipData.file_upload"
            :tip-files="tipFiles"
            class="mt-4"
        />
    </div>
</template>

<script setup lang="ts">
import KbLayout from "@/Layouts/KbLayout.vue";
import TipDetailsTitle from "@/Components/TechTips/TipDetailsTitle.vue";
import TipEquipmentList from "@/Components/TechTips/TipEquipmentList.vue";
import TipDetails from "@/Components/TechTips/TipDetails.vue";
import TipFiles from "@/Components/TechTips/TipFiles.vue";

defineProps<{
    tipData: techTip;
    tipEquipment: equipment[];
    tipFiles: fileUpload[];
}>();
</script>

<script lang="ts">
export default { layout: KbLayout };
</script>
