<template>
    <div>
        <div class="row">
            <div class="col-12">
                <p class="text-center">
                    Please check your email, a verification code has been sent
                </p>
                <VerificationCodeForm :allow-remember="allowRemember" />
                <div class="text-center mt-2">
                    <Link :href="$route('dashboard')">Resend Code</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import VerificationCodeForm from "@/Forms/Auth/VerificationCodeForm.vue";

defineProps<{
    allowRemember: boolean;
}>();
</script>

<script lang="ts">
export default { layout: [GuestLayout, AuthLayout] };
</script>
