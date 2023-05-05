<template>
    <button
        class="btn btn-danger btn-pill btn-sm w-75 my-1"
        title="Manage Customer"
        v-tooltip
        @click="manageCustomerModal?.show"
    >
        <fa-icon icon="fa-tasks" />
        Manage
    </button>
    <Modal ref="manageCustomerModal" title="Manage Customer">
        <div class="text-center mt-2">
            <Overlay :loading="loading">
                <DeletedItems />
                <LinkCustomer />
                <DisableCustomer @disabling="manageCustomerModal?.hide()" />
            </Overlay>
        </div>
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import Overlay from "@/Components/Base/Overlay.vue";
import DeletedItems from "./Manage/DeletedItems.vue";
import LinkCustomer from "@/Components/Customer/Manage/LinkCustomer.vue";
import DisableCustomer from "./Manage/DisableCustomer.vue";
import { ref, provide } from "vue";
import { toggleManageLoadKey } from '@/SymbolKeys/CustomerKeys';

const manageCustomerModal = ref<InstanceType<typeof Modal> | null>(null);
const loading = ref<boolean>(false);

//  Loading state for the Modal
const toggleLoading = (set: boolean) => {
    loading.value = set;
};
provide(toggleManageLoadKey, toggleLoading);
</script>
