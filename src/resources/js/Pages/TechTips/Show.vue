<template>
    <div id="tech-tip-wrapper">
        <Head :title="tipData.subject" />
        <div class="border-bottom border-secondary-subtle pb-2">
            <TipManagement
                v-if="showManage"
                :tip-data="tipData"
                :permissions="permissions"
                class="float-end"
            />
            <TipDetailsTitle :tip-data="tipData" />
        </div>
        <TipEquipmentList :tip-equipment="tipEquipment" />
        <TipDetails :tip-data="tipData" class="mt-4" />
        <TipFiles :tip-files="tipFiles" class="mt-4" />
        <TipComments
            v-if="
                (tipData.allow_comments && permissions.comment) ||
                tipComments.length
            "
            :tip-comments="tipComments"
            :tip-slug="tipData.slug"
            :permissions="permissions"
            class="mt-4"
        />
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TipDetailsTitle from "@/Components/TechTips/TipDetailsTitle.vue";
import TipManagement from "@/Components/TechTips/TipManagement.vue";
import TipEquipmentList from "@/Components/TechTips/TipEquipmentList.vue";
import TipDetails from "@/Components/TechTips/TipDetails.vue";
import TipFiles from "@/Components/TechTips/TipFiles.vue";
import TipComments from "@/Components/TechTips/TipComments.vue";
import { ref, reactive, computed } from "vue";

const props = defineProps<{
    tipData: techTip;
    tipEquipment: equipment[];
    tipFiles: fileUpload[];
    tipComments: tipComment[];
    permissions: techTipPermissions;
}>();

const showManage = computed(() => {
    if (
        props.permissions.manage ||
        props.permissions.update ||
        props.permissions.delete
    ) {
        return true;
    }

    return false;
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
