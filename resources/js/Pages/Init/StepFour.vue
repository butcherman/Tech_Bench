<template>
    <div>
        <h5 class="text-center">User Security Settings</h5>
        <p class="text-center">
            Last step of this wizard is to setup User Security Settings. Adjust
            the password policy as you see fit.
        </p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <PasswordPolicyForm :policy="policy" @success="nextStep" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import PasswordPolicyForm from "@/Forms/Admin/User/PasswordPolicyForm.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    policy: passwordPolicy;
}>();

const nextStep = () => {
    router.get(route("init.step-5"));
};
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
