<template>
    <div>
        <h5 class="text-center">Create Customer</h5>
        <p class="text-center">
            Use this form to create a new Customer Instance.
        </p>
        <customerCustomerDetailsForm :select-id="selectId" />
    </div>
</template>

<script setup lang="ts">
import customerCustomerDetailsForm from "../Forms/customer.customerDetailsForm.vue";
import { usePage } from "@inertiajs/vue3";

const selectId: boolean = usePage<{ selectId: boolean }>().props.selectId;
</script>
