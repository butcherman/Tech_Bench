<template>
    <EditButton
        @click="editCustomerModal?.show"
        pill
        class="w-75 btn-sm"
        title="Edit Customer Details"
        v-tooltip
    />
    <Modal ref="editCustomerModal" title="Update Customer Details">
        <EditCustomerForm @success="editCustomerModal?.hide" />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import EditButton from "@/Components/Base/Buttons/EditButton.vue";
import EditCustomerForm from "@/Forms/Customer/EditCustomerForm.vue";
import { ref } from "vue";

const editCustomerModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
