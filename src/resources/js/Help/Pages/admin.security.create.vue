<template>
    <div>
        <h5 class="text-center">Upload Certificate</h5>
        <p class="text-center">
            Use this form to upload a new SSL Certificate.
        </p>
        <p class="text-center">
            Reboot Tech Bench for the certificate to become active.
        </p>
        <certificateForm />
    </div>
</template>

<script setup lang="ts">
import certificateForm from "../Forms/admin.security.certificateForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
