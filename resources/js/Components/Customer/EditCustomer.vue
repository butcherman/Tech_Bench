<template>
    <button
        class="btn btn-warning btn-pill btn-sm w-75 my-1"
        title="Edit Customer Details"
        v-tooltip
        @click="editCustomerModal?.show"
    >
        <fa-icon icon="fa-edit" />
        Edit
    </button>
    <Modal ref="editCustomerModal" title="Update Customer Details">
        <EditCustomerForm @success="editCustomerModal?.hide()" />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import EditCustomerForm from "@/Forms/Customer/EditCustomerForm.vue";
import { ref } from "vue";

const editCustomerModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
