<template>
    <div>
        <CustomerSiteList
            title-text="Sites with this Equipment"
            empty-text="No Sites attached to this Equipment"
        >
            <template #status>
                <RefreshButton :only="['siteList']" />
            </template>
            <template #add-button>
                <button
                    v-if="permissions.equipment.update"
                    class="btn btn-info rounded-5 btn-sm"
                    @click="manageSitesModal?.show"
                >
                    <fa-icon icon="share-nodes" />
                    Manage Sites
                </button>
            </template>
        </CustomerSiteList>
        <Modal
            v-if="permissions.equipment.update"
            ref="manageSitesModal"
            title="Manage Sites"
        >
            <CustomerEquipmentSitesForm
                :equipment="equipment"
                :customer="customer"
                :site-list="customer.customer_site"
                :current-list="siteList"
                @success="manageSitesModal?.hide"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import CustomerSiteList from "@/Components/Customer/CustomerSiteList.vue";
import CustomerEquipmentSitesForm from "@/Forms/Customer/CustomerEquipmentSitesForm.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { ref } from "vue";
import { customer, siteList, permissions } from "@/State/CustomerState";

defineProps<{
    equipment: customerEquipment;
}>();

const manageSitesModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
