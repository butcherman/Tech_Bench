<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerDetails />
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center">{{ equipment.equip_name }}</h5>
                        <CustomerEquipmentData
                            :equipment-data="equipmentData"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="customer.site_count > 1" class="row my-4">
            <div class="col">
                <CustomerSiteList
                    title-text="Sites with this Equipment"
                    empty-text="No Sites attached to this Equipment"
                >
                    <template #add-button>
                        <button
                            class="btn btn-primary rounded-5 btn-sm"
                            @click="manageSitesModal?.show"
                        >
                            <fa-icon icon="share-nodes" />
                            Manage Sites
                        </button>
                    </template>
                </CustomerSiteList>
            </div>
        </div>
        <Modal ref="manageSitesModal" title="Manage Sites">
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
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import CustomerEquipmentData from "@/Components/Customer/CustomerEquipmentData.vue";
import CustomerSiteList from "@/Components/Customer/CustomerSiteList.vue";
import CustomerEquipmentSitesForm from "@/Forms/Customer/CustomerEquipmentSitesForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { customer, siteList } from "@/State/CustomerState";
import { ref } from "vue";

defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();

const manageSitesModal = ref<InstanceType<typeof Modal> | null>(null);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
