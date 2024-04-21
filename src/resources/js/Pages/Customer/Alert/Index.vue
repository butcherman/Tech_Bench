<template>
    <div id="customer-wrapper">
        <Head :title="`${customer.name} Alerts`" />
        <CustomerDetails />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Current Alerts
                            <AddButton
                                class="float-end"
                                text="Create Alert"
                                pill
                                small
                                @click="triggerAlertForm()"
                            />
                        </div>
                        <!-- <div class="" -->
                        <div v-if="!customerAlerts.length">
                            <h5 class="text-center">No Customer Alerts</h5>
                        </div>
                        <ul class="list-group">
                            <li
                                v-for="alert in customerAlerts"
                                class="list-group-item"
                            >
                                <div
                                    class="alert"
                                    :class="`alert-${alert.type}`"
                                >
                                    {{ alert.message }}
                                    <DeleteBadge
                                        class="float-end"
                                        title="Remove Alert"
                                        v-tooltip
                                        @click="removeAlert(alert)"
                                    />
                                    <EditBadge
                                        class="float-end"
                                        title="Edit Alert"
                                        v-tooltip
                                        @click="triggerAlertForm(alert)"
                                    />
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <Modal title="Alert" ref="alertModal" @hidden="hideAlertForm">
            <CustomerAlertForm
                v-if="activeModal"
                :customer="customer"
                :alert="activeAlert"
                @success="hideAlertForm"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import CustomerAlertForm from "@/Forms/Customer/CustomerAlertForm.vue";
import { ref } from "vue";
import { customer, customerAlerts } from "@/State/CustomerState";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const alertModal = ref<InstanceType<typeof Modal> | null>(null);
const activeModal = ref<boolean>(false);
const activeAlert = ref<customerAlert | null>(null);

const triggerAlertForm = (alert: customerAlert | null = null) => {
    activeModal.value = true;
    if (alert !== null) {
        console.log(alert);
        activeAlert.value = alert;
    }

    alertModal.value?.show();
};

const hideAlertForm = () => {
    activeModal.value = false;
    activeAlert.value = null;
    alertModal.value?.hide();
};

const removeAlert = (alert: customerAlert) => {
    verifyModal("This alert will no longer show for the customer").then(
        (res) => {
            if (res) {
                console.log("go for it");
                router.delete(
                    route("customers.alerts.destroy", [
                        customer.value.slug,
                        alert.alert_id,
                    ])
                );
            }
        }
    );
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
