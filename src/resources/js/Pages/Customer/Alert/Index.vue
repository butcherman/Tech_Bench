<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerAlertForm from "@/Forms/Customer/CustomerAlertForm.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { ref, useTemplateRef } from "vue";
import { alerts, customer } from "@/Composables/Customer/CustomerData.module";
import { getStatusType, getStatusIcon } from "@/Composables/styleData.module";

const modal = useTemplateRef("alert-modal");
const activeAlert = ref<customerAlert | undefined>();

/**
 * Edit an alert.
 */
const onEditAlert = (alert: customerAlert): void => {
    activeAlert.value = alert;
    modal.value?.show();
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <CustomerDetails class="border-b border-slate-400" />
        <div class="flex justify-center">
            <Card class="tb-card" title="Current Alerts">
                <template #append-title>
                    <AddButton
                        text="Add Alert"
                        size="small"
                        pill
                        @click="modal?.show"
                    />
                </template>
                <ResourceList :list="alerts" empty-text="No Alerts">
                    <template #list-item="{ item }">
                        <div class="flex">
                            <div
                                class="flex grow rounded-lg"
                                :class="getStatusType(item.type)"
                            >
                                <div class="px-3 py-2">
                                    <fa-icon :icon="getStatusIcon(item.type)" />
                                </div>
                                <div class="grow text-center py-2">
                                    {{ item.message }}
                                </div>
                                <div class="px-3 py-2">
                                    <fa-icon :icon="getStatusIcon(item.type)" />
                                </div>
                            </div>
                            <div class="py-2 px-3">
                                <EditBadge
                                    v-tooltip="'Edit Alert'"
                                    @click="onEditAlert(item)"
                                />
                                <DeleteBadge
                                    class="ms-1"
                                    :href="
                                        $route('customers.alerts.destroy', [
                                            customer.slug,
                                            item.alert_id,
                                        ])
                                    "
                                    confirm
                                    delete-method
                                />
                            </div>
                        </div>
                    </template>
                </ResourceList>
            </Card>
        </div>
        <Modal ref="alert-modal" @hidden="activeAlert = undefined">
            <CustomerAlertForm
                v-if="modal?.isOpen || activeAlert"
                :customer="customer"
                :alert="activeAlert"
                @success="modal?.hide"
            />
        </Modal>
    </div>
</template>
