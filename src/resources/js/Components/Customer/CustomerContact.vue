<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <AlertButton
                    v-if="changeAlert.contacts"
                    title="Contacts Updated.  Refresh for most recent data"
                />
                <RefreshButton
                    :only="['contacts']"
                    @loading-start="toggleLoading('contacts')"
                    @loading-complete="clearAlert('contacts')"
                />
                Contacts:
                <CustomerContactCreate
                    v-if="permissions.contact.create"
                    class="float-end"
                />
            </div>
            <Overlay :loading="loading.contacts" class="h-100">
                <div v-if="!contacts.length">
                    <h6 class="text-center">No Contacts</h6>
                </div>
                <div
                    v-for="cont in contacts"
                    :key="cont.cont_id"
                    @click="showContact(cont)"
                >
                    <div class="card m-1 pointer">
                        <div class="card-body p-2">
                            <div class="row m-0 p-0">
                                <div class="col-1">
                                    <fa-icon icon="user-tie" />
                                </div>
                                <div class="col">
                                    {{ cont.name }}
                                    <span v-if="cont.title"
                                        >({{ cont.title }})</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Overlay>
        </div>
        <CustomerContactModal
            v-if="activeContact"
            ref="contactDetailsModal"
            :contact="activeContact"
            @hidden="activeContact = null"
            @edit-contact="editContact"
            @delete-contact="deleteContact"
        />
        <CustomerContactEdit
            v-if="editingContact"
            ref="editContactModal"
            :contact="editingContact"
            @hidden="editingContact = null"
            @success="editContactModal?.closeModal()"
        />
    </div>
</template>

<script setup lang="ts">
import verifyModal from "@/Modules/verifyModal";
import CustomerContactCreate from "./CustomerContactCreate.vue";
import CustomerContactModal from "./CustomerContactModal.vue";
import CustomerContactEdit from "./CustomerContactEdit.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import AlertButton from "../_Base/Buttons/AlertButton.vue";
import Overlay from "../_Base/Loaders/Overlay.vue";
import {
    permissions,
    contacts,
    customer,
    loading,
    toggleLoading,
    changeAlert,
    clearAlert,
} from "@/State/CustomerState";
import { ref, nextTick } from "vue";
import { router } from "@inertiajs/vue3";

const activeContact = ref<customerContact | null>(null);
const editingContact = ref<customerContact | null>(null);
const contactDetailsModal = ref<InstanceType<
    typeof CustomerContactModal
> | null>(null);
const editContactModal = ref<InstanceType<typeof CustomerContactEdit> | null>(
    null
);

const showContact = (cont: customerContact) => {
    activeContact.value = cont;
    nextTick(() => contactDetailsModal.value?.openModal());
};

const editContact = (cont: customerContact) => {
    contactDetailsModal.value?.closeModal();
    editingContact.value = cont;
    nextTick(() => editContactModal.value?.openModal());
};

const deleteContact = (cont: customerContact) => {
    verifyModal("Please Confirm you want to delete this contact").then(
        (res) => {
            if (res) {
                contactDetailsModal.value?.closeModal();
                toggleLoading("contacts");
                router.delete(
                    route("customers.contacts.destroy", [
                        customer.value.slug,
                        cont.cont_id,
                    ]),
                    {
                        only: ["flash", "contacts"],
                        onFinish: () => toggleLoading("contacts"),
                    }
                );
            }
        }
    );
};
</script>
