<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Title</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <tr
                v-for="cont in contacts"
                :key="cont.cont_id"
                class="pointer"
                title="Click to Open"
                v-tooltip
            >
                <td class="m-0" @click="openContact(cont)">{{ cont.name }}</td>
                <td class="m-0" @click="openContact(cont)">{{ cont.title }}</td>
                <td class="m-0">
                    <a
                        :href="
                            $route('customers.contacts.download', cont.cont_id)
                        "
                        class="float-end badge text-info pointer"
                        title="Download Contact"
                        v-tooltip
                    >
                        <fa-icon icon="fa-download" />
                    </a>
                    <a
                        :href="`mailto:${cont.email}`"
                        title="Click to send email"
                        class="text-info"
                        v-tooltip
                    >
                        <fa-icon icon="fa-envelope" />
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
    <Modal ref="contactModal" :title="selectedContact?.name">
        <h4>
            {{ selectedContact?.name }}
            <a
                :href="
                    $route(
                        'customers.contacts.download',
                        selectedContact?.cont_id || 0
                    )
                "
                class="float-end badge text-info pointer"
                title="Download Contact"
                v-tooltip
            >
                <fa-icon icon="fa-download" />
            </a>
        </h4>
        <blockquote class="blockquote">{{ selectedContact?.title }}</blockquote>
        <div>
            <table class="table">
                <tbody>
                    <tr>
                        <th>Email Address:</th>
                        <td>
                            <a
                                :href="`mailto:${selectedContact?.email}`"
                                title="Click to send email"
                                v-tooltip
                            >
                                {{ selectedContact?.email }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Phone Numbers:</th>
                        <td>
                            <div
                                v-for="phone in selectedContact?.customer_contact_phone"
                                :id="phone.id.toString()"
                            >
                                <span
                                    :title="phone.phone_number_type.description"
                                    v-tooltip
                                >
                                    <fa-icon
                                        :icon="
                                            phone.phone_number_type.icon_class
                                        "
                                    />
                                </span>
                                <span class="ms-2">
                                    <a
                                        :href="`tel:${phone.phone_number}`"
                                        title="Click to Call"
                                        v-tooltip
                                    >
                                        {{ phone.formatted }}
                                    </a>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="text-strong">Note:</div>
                            {{ selectedContact?.note }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <EditContact
                    v-if="permission.contact.update"
                    :contact="selectedContact"
                    @openEdit="contactModal?.hide()"
                />
                <DeleteButton class="btn-sm mx-2 w-25" @click="deleteContact" />
            </div>
        </div>
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import EditContact from "@/Components/Customer/Contacts/EditContact.vue";
import DeleteButton from "@/Components/Base/Buttons/DeleteButton.vue";
import { ref, inject } from "vue";
import { verifyModal } from "@/Modules/verifyModal.module";
import { router } from "@inertiajs/vue3";
import {
    custPermissionsKey,
    toggleContactsLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import type {
    customerContactType,
    customerPermissionType,
    voidFunctionType,
} from "@/Types";

defineProps<{
    contacts: customerContactType[];
}>();

const $route = route;
const permission = inject(custPermissionsKey) as customerPermissionType;
const toggleLoad = inject(toggleContactsLoadKey) as voidFunctionType;
const contactModal = ref<InstanceType<typeof Modal> | null>(null);
const selectedContact = ref<customerContactType>();

const openContact = (contact: customerContactType) => {
    selectedContact.value = contact;
    contactModal.value?.show();
};

const deleteContact = () => {
    verifyModal("Are you sure you want to delete this contact?").then((res) => {
        if (res) {
            toggleLoad();
            contactModal.value?.hide();
            router.delete(
                route(
                    "customers.contacts.destroy",
                    selectedContact.value?.cont_id
                ),
                {
                    onFinish: () => toggleLoad(),
                }
            );
        }
    });
};
</script>

<style>
.text-strong {
    font-weight: bold;
}
</style>
