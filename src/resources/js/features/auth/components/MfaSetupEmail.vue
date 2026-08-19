<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import TwoFactorForm from "@/features/auth/forms/TwoFactorForm.vue";
import { verify } from "@/wayfinder/routes/two-factor/setup/email";

const props = defineProps<{
    allowRemember: boolean;
}>();
</script>

<template>
    <Card title="Setup MFA">
        <h5 class="text-center">
            A verification code has been sent to your email address.
        </h5>
        <p class="text-center">
            Please input the code to complete your MFA Setup
        </p>
        <div>
            <TwoFactorForm
                :allow-remember="allowRemember"
                :submit-route="verify.url()"
            />
        </div>
    </Card>
</template>
