<template>
    <div>
        <h5 class="text-center">Password Policy</h5>
        <p class="text-center">
            Modify the minimum password requirements on this page
        </p>
        <AdminUserPasswordPolicyForm />
    </div>
</template>

<script setup lang="ts">
import AdminUserPasswordPolicyForm from "../Forms/admin.user.passwordPolicyForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
