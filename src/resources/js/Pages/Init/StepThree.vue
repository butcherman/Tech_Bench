<template>
    <div class="text-center">
        <h1 class="font-bold text-2xl">User Settings</h1>
        <p class="mb-3">
            Now we will setup User Security Settings. Adjust the password policy
            as you see fit.
        </p>
        <PasswordPolicyForm
            :policy="policy"
            init
            @success="router.get($route('init.step-4'))"
        />
    </div>
</template>

<script setup lang="ts">
import PasswordPolicyForm from "@/Forms/Admin/User/PasswordPolicyForm.vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    policy: passwordPolicy;
}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
