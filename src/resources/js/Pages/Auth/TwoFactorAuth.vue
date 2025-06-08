<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import TwoFactorForm from "@/Forms/Auth/TwoFactorForm.vue";

defineProps<{
    allowRemember: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex items-center justify-center h-screen">
        <Card class="tb-card-sm" title="Two Factor Authentication">
            <!-- <h5 class="text-center">
                A verification code has been sent to your email address.
            </h5>
            <p class="text-center">Please input the code below.</p> -->
            <h5 class="text-center">
                Input the code from your Authenticator App
            </h5>
            <div>
                <TwoFactorForm :allow-remember="allowRemember" />
            </div>
            <!-- <div class="text-center mt-4">
                <BaseButton
                    :href="$route('dashboard')"
                    class="w-3/4"
                    variant="warning"
                >
                    Send New Verification Code
                </BaseButton>
            </div> -->
        </Card>
    </div>
</template>
