<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import ContactDetailsModal from "./ContactDetailsModal.vue";
import ContactFormModal from "./ContactFormModal.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { contactList } from "@/Composables/Customer/CustomerData.module";
import { Deferred } from "@inertiajs/vue3";
import { ref, useTemplateRef } from "vue";
import {
    clearNotification,
    notificationStatus,
} from "@/Composables/Customer/CustomerBroadcasting.module";

const formModal = useTemplateRef("contact-form-modal");
const detailsModal = useTemplateRef("contact-details-modal");

/*
|-------------------------------------------------------------------------------
| Loading State
|-------------------------------------------------------------------------------
*/
const isLoading = ref<boolean>(false);

/**
 * Start the loading process when the refresh button clicked.
 */
const onRefreshStart = (): void => {
    isLoading.value = true;
};

/**
 * End the loading process and clear the alert icon.
 */
const onRefreshEnd = (): void => {
    isLoading.value = false;
    clearNotification("contacts");
};
</script>

<template>
    <Card>
        <template #title>
            <AlertButton v-if="notificationStatus.contacts" />
            <RefreshButton
                flat
                :only="['contactList']"
                @loading-start="onRefreshStart"
                @loading-complete="onRefreshEnd"
            />
            Contacts
            <AddButton
                class="float-end"
                size="small"
                text="Add Contact"
                pill
                @click="formModal?.show()"
            />
        </template>
        <Deferred data="contactList">
            <template #fallback>
                <div class="flex justify-center">
                    <AtomLoader />
                </div>
            </template>
            <Overlay :loading="isLoading" class="h-full">
                <Overlay :loading="isLoading" class="h-full">
                    <ResourceList
                        empty-text="No Contacts"
                        :list="contactList"
                        label-field="name"
                        compact
                        paginate
                        :per-page="5"
                    >
                        <template #list-item="{ item }">
                            <BaseButton
                                class="w-full text-start"
                                variant="none"
                                @click="detailsModal?.show(item)"
                            >
                                <fa-icon icon="user-tie" class="me-4" />
                                {{ item.name }}
                                <span v-if="item.title">
                                    ({{ item.title }})
                                </span>
                            </BaseButton>
                        </template>
                    </ResourceList>
                </Overlay>
            </Overlay>
        </Deferred>
        <ContactFormModal ref="contact-form-modal" />
        <ContactDetailsModal
            ref="contact-details-modal"
            @edit="formModal?.show"
        />
    </Card>
</template>
