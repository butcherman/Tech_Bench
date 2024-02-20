<template>
    <div>
        <h5 class="text-center">Edit Customer</h5>
        <p class="text-center">
            Customer information can be updated here. To update the information
            for a customer site, go to the Edit Customer Site form.
        </p>
        <customerCustomerDetailsForm edit />
    </div>
</template>

<script setup lang="ts">
import customerCustomerDetailsForm from "../Forms/customer.customerDetailsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
