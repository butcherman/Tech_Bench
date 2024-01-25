<template>
    <div class="row justify-content-center align-items-center h-100 p-3">
        <Head title="Forgot Password" />
        <div class="col-md-6">
            <div class="card">
                <div class="card-body h-100">
                    <div class="card-title">Two Factor Authentication</div>
                    <h5 class="text-center">
                        A verification code has been sent to your email address.
                    </h5>
                    <p class="text-center">Please input the code below.</p>
                    <TwoFactorForm :allow-remember="allowRemember" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import TwoFactorForm from "@/Forms/Auth/TwoFactorForm.vue";
import { useAppStore } from "@/Store/AppStore";

const app = useAppStore();

defineProps<{
    allowRemember: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
