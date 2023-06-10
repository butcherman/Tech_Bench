<template>
    <Head title="Reset Password" />
    <div>
        <ResetPasswordForm :token="token" :email="email" />
        <div class="row justify-content-center">
            <div class="col grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Password Rules:</h5>
                        <ul>
                            <li v-for="rule in passwordRules" class="mx-2">
                                {{ rule }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import ResetPasswordForm from "@/Forms/Auth/ResetPasswordForm.vue";

defineProps<{
    token: string;
    email: string;
    passwordRules: string[];
}>();
</script>

<script lang="ts">
export default { layout: [GuestLayout, AuthLayout] };
</script>
