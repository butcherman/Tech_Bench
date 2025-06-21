<script setup lang="ts">
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import verifyModal from "@/Modules/verifyModal";
import { ref, useTemplateRef } from "vue";
import { router } from "@inertiajs/vue3";
import {
    customer,
    permissions,
} from "@/Composables/Customer/CustomerData.module";

const emit = defineEmits<{
    edit: [customerContact];
}>();

const modal = useTemplateRef("contact-details-modal");
const activeContact = ref<customerContact | undefined>();

/**
 * Show the details for the selected contact.
 */
const show = (showContact: customerContact): void => {
    activeContact.value = showContact;
    modal.value?.show();
};

/**
 * Open the form to edit the contact.
 */
const onEditClicked = (): void => {
    if (activeContact.value) {
        emit("edit", activeContact.value);
        modal.value?.hide();
    }
};

/**
 * Verify and delete the selected contact.
 */
const onDeleteClicked = (): void => {
    verifyModal("Do you want to Delete this Contact?").then((res) => {
        if (res) {
            router.delete(
                route("customers.contacts.destroy", [
                    customer.value.slug,
                    activeContact.value?.cont_id,
                ]),
                {
                    only: ["contactList"],
                    onFinish: () => modal.value?.hide(),
                    preserveScroll: true,
                }
            );
        }
    });
};

defineExpose({ show });
</script>

<template>
    <Modal
        ref="contact-details-modal"
        title="Contact Details"
        @hidden="activeContact = undefined"
    >
        <div v-if="activeContact">
            <h4>{{ activeContact?.name }}</h4>
            <blockquote>{{ activeContact?.title }}</blockquote>
            <div v-if="activeContact?.local">
                <span class="text-success">
                    <fa-icon icon="check" />
                    Local Contact
                </span>
            </div>
            <div v-if="activeContact?.decision_maker">
                <span class="text-success">
                    <fa-icon icon="check" />
                    Decision Maker
                </span>
            </div>
            <hr />
            <div class="my-2">
                <table class="table-fixed">
                    <tbody>
                        <tr>
                            <th class="text-start">Email Address:</th>
                            <td class="ps-2">
                                <a
                                    :href="`mailto:${activeContact?.email}`"
                                    class="text-blue-700"
                                    v-tooltip="'Send Email'"
                                >
                                    {{ activeContact?.email }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone Numbers:</th>
                            <td class="ps-2">
                                <div
                                    v-for="phone in activeContact?.customer_contact_phone"
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
                    </tbody>
                </table>
            </div>
            <div class="grid grid-cols-2">
                <EditButton
                    v-if="permissions.contact.update"
                    class="mx-2"
                    size="small"
                    pill
                    @click="onEditClicked"
                />
                <DeleteButton
                    v-if="permissions.contact.delete"
                    class="mx-2"
                    size="small"
                    pill
                    @click="onDeleteClicked"
                />
            </div>
        </div>
    </Modal>
</template>
