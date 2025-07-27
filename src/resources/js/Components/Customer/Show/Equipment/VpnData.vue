<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import CustomerSearchModal from "../../Search/CustomerSearchModal.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import verifyModal from "@/Modules/verifyModal";
import VpnDataForm from "@/Forms/Customer/VpnDataForm.vue";
import { router } from "@inertiajs/vue3";
import { useTemplateRef } from "vue";
import {
    allowShareVpn,
    customer,
    permissions,
    vpnData,
} from "@/Composables/Customer/CustomerData.module";

const modal = useTemplateRef("vpn-data-modal");
const editModal = useTemplateRef("edit-data-modal");
const shareModal = useTemplateRef("share-data-modal");

/**
 * Open the form to Share the VPN Data with another customer profile
 */
const onShareData = (): void => {
    modal.value?.hide();
    shareModal.value?.show();
};

const onShareSelected = (selected: customer): void => {
    router.put(
        route("customers.vpn-data.share", [
            selected.slug,
            vpnData.value?.vpn_id,
        ])
    );
};

/**
 * Open form to edit VPN Data
 */
const onEditData = (): void => {
    modal.value?.hide();
    editModal.value?.show();
};

/**
 * Delete the current VPN Data
 */
const onDeleteData = (): void => {
    verifyModal("Do you want to delete the VPN Data?").then((res) => {
        if (res) {
            router.delete(
                route("customers.vpn-data.destroy", [
                    customer.value?.slug,
                    vpnData.value?.vpn_id,
                ])
            );
        }
    });
};
</script>

<template>
    <div v-if="vpnData">
        <BaseButton
            text="VPN Data"
            size="small"
            variant="help"
            pill
            @click="modal?.show()"
        />
        <Modal ref="vpn-data-modal" title="VPN Data">
            <div class="flex flex-row-reverse">
                <DeleteBadge
                    v-if="permissions.equipment.delete"
                    class="ms-1"
                    v-tooltip="'Delete VPN Data'"
                    @click="onDeleteData()"
                />
                <EditBadge
                    v-if="permissions.equipment.update"
                    class="ms-1"
                    v-tooltip="'Edit VPN Data'"
                    @click="onEditData()"
                />
                <BaseBadge
                    v-if="allowShareVpn && permissions.details.manage"
                    icon="share-nodes"
                    v-tooltip="'Share VPN Data'"
                    @click="onShareData()"
                />
            </div>
            <div class="flex justify-center">
                <table class="table-fixed border-collapse">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-end">VPN Client:</th>
                            <td class="ps-2">{{ vpnData.vpn_client_name }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-end">VPN URL:</th>
                            <td class="ps-2">
                                {{ vpnData.vpn_portal_url }}
                                <ClipboardCopy
                                    :value="vpnData.vpn_portal_url"
                                    class="float-end ps-1"
                                />
                            </td>
                        </tr>
                        <template v-if="vpnData.vpn_username">
                            <tr class="border-b">
                                <th class="text-end">VPN Username:</th>
                                <td class="ps-2">
                                    {{ vpnData.vpn_username }}
                                    <ClipboardCopy
                                        :value="vpnData.vpn_username"
                                        class="float-end ps-1"
                                    />
                                </td>
                            </tr>
                            <tr class="border-b">
                                <th class="text-end">VPN Password:</th>
                                <td class="ps-2">
                                    {{ vpnData.vpn_password }}
                                    <ClipboardCopy
                                        :value="vpnData.vpn_password"
                                        class="float-end ps-1"
                                    />
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div v-if="vpnData.notes" class="mt-2 border rounded-xl p-2">
                <h6>Additional Notes:</h6>
                <div v-html="vpnData.notes" />
            </div>

            <div class="text-xs mt-2 border-t border-t-slate-200">
                <strong>Note:</strong> If login credentials are not listed, you
                should be provided with your own unique credentials.
            </div>
        </Modal>
        <Modal ref="edit-data-modal">
            <VpnDataForm :customer="customer" :vpn-data="vpnData" />
        </Modal>
        <CustomerSearchModal
            ref="share-data-modal"
            title="Select Customer to Share VPN Data With"
            @selected="onShareSelected"
        />
    </div>
</template>
