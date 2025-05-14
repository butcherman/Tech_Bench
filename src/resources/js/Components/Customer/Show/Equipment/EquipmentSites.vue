<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import EquipmentSitesForm from "@/Forms/Customer/EquipmentSitesForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import SiteList from "../SiteList.vue";
import { useTemplateRef } from "vue";
import {
    customer,
    permissions,
    siteList,
} from "@/Composables/Customer/CustomerData.module";

defineProps<{
    equipment: customerEquipment;
}>();

const modal = useTemplateRef("manage-sites-modal");
</script>

<template>
    <div>
        <SiteList title="Sites With This Equipment">
            <template #append-title>
                <BaseButton
                    v-if="permissions.equipment.update"
                    text="Manage Sites"
                    size="small"
                    icon="share-nodes"
                    pill
                    @click="modal?.show()"
                />
            </template>
        </SiteList>
        <Modal
            ref="manage-sites-modal"
            title="Manage Equipment Sites"
            size="large"
        >
            <EquipmentSitesForm
                :equipment="equipment"
                :customer="customer"
                :site-list="siteList"
                @success="modal?.hide()"
            />
        </Modal>
    </div>
</template>
