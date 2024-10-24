<template>
    <div>
        <h5 class="text-center">Customer Settings</h5>
        <p class="text-center">
            Use this form to adjust global customer settings.
        </p>
        <customerSettingsForm />
    </div>
</template>

<script setup lang="ts">
import customerSettingsForm from "../Forms/customer.customerSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
