<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import TwoFactorForm from "@/features/auth/forms/TwoFactorForm.vue";

defineProps<{
    allowRemember: boolean;
    via: "authenticator" | "email";
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card size="md">
            <div class="flex flex-col gap-2">
                <div v-if="via === 'email'">
                    <h5 class="text-center">
                        A verification code has been sent to your email address.
                    </h5>
                    <p class="text-center">Please enter the code below.</p>
                </div>
                <div v-if="via === 'authenticator'">
                    <h5 class="text-center">
                        Input the code from your Authenticatior App
                    </h5>
                </div>
                <div>
                    <TwoFactorForm :allow-remember="allowRemember" :via="via" />
                </div>
                <div v-if="via === 'email'" class="text-center">
                    <BaseButton
                        text="Send New Verification Code"
                        variant="warning"
                    />
                </div>
            </div>
        </Card>
    </div>
</template>
