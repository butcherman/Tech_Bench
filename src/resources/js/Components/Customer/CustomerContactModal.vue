<template>
    <Modal
        title="Contact Details"
        ref="contactDetailsModal"
        @hidden="$emit('hidden')"
    >
        <h4>{{ contact.name }}</h4>
        <blockquote class="blockquote mb-0">{{ contact.title }}</blockquote>
        <div v-if="contact.local">
            <span class="text-success">
                <fa-icon icon="check" />
                Local Contact
            </span>
        </div>
        <div v-if="contact.decision_maker">
            <span class="text-success">
                <fa-icon icon="check" />
                Decision Maker
            </span>
        </div>
        <hr />
        <div>
            <table class="table">
                <tbody>
                    <tr>
                        <th>Email Address:</th>
                        <td>
                            <a
                                :href="`mailto:${contact.email}`"
                                title="Send Email"
                                v-tooltip
                            >
                                {{ contact.email }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Phone Numbers:</th>
                        <td>
                            <div
                                v-for="phone in contact.customer_contact_phone"
                                :key="phone.id"
                            >
                                <span class="ms-2">
                                    <a
                                        :href="`tel:${phone.phone_number}`"
                                        :title="`Call ${phone.phone_number_type.description}`"
                                        v-tooltip
                                    >
                                        <fa-icon
                                            :icon="
                                                phone.phone_number_type
                                                    .icon_class
                                            "
                                        />
                                        {{ phone.formatted }}
                                    </a>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="contact.note">
                        <td colspan="2">
                            <strong>Note:</strong>
                            {{ contact.note }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <EditButton
                    v-if="permissions.contact.update"
                    class="w-50 m-1"
                    text="Edit Contact"
                    @click="$emit('edit-contact', contact)"
                />
                <DeleteButton
                    v-if="permissions.contact.delete"
                    text="Delete Contact"
                    class="w-50 m-1"
                    @click="$emit('delete-contact', contact)"
                />
            </div>
        </div>
    </Modal>
</template>

<script setup lang="ts">
import EditButton from "../_Base/Buttons/EditButton.vue";
import Modal from "../_Base/Modal.vue";
import { ref } from "vue";
import { permissions } from "@/State/CustomerState";
import DeleteButton from "../_Base/Buttons/DeleteButton.vue";

defineEmits(["hidden", "edit-contact", "delete-contact"]);
defineProps<{
    contact: customerContact;
}>();

const contactDetailsModal = ref<InstanceType<typeof Modal> | null>(null);

const openModal = () => {
    contactDetailsModal.value?.show();
};

const closeModal = () => {
    contactDetailsModal.value?.hide();
};

defineExpose({ openModal, closeModal });
</script>
