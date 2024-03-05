<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <div id="manage-customer-dropdown" class="dropdown float-end">
                <button
                    class="btn rounded-circle dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                >
                    <fa-icon icon="ellipsis-vertical" />
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <span
                            class="dropdown-item pointer"
                            @click="disableEquipment"
                        >
                            Disable Equipment
                        </span>
                    </li>
                </ul>
            </div>
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
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();

const manageSitesModal = ref<InstanceType<typeof Modal> | null>(null);

const disableEquipment = () => {
    verifyModal("This Equipment will no longer be accessible").then((res) => {
        if (res) {
            router.delete(
                route("customers.equipment.destroy", [
                    customer.value.slug,
                    props.equipment.cust_equip_id,
                ])
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
