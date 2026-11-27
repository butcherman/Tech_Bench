<script setup lang="ts">
import TwoFactorForm from "@/features/auth/forms/TwoFactorForm.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { ref, reactive, onMounted, computed } from "vue";
import { store } from "@/wayfinder/routes/two-factor/login";

defineOptions({ layout: AuthLayout });

const props = defineProps<{
    allowRemember: boolean;
    via: MultiFactorMethod;
}>();

const mfaText = computed(() => {
    return {
        email: "A verification code has been sent to your email",
        authenticator:
            "Please input the verification code from your Authenticator App",
    }[props.via];
});
</script>

<template>
    <div class="flex flex-col gap-5">
        <h3 class="text-center">Multi-Factor Authentication</h3>
        <h5 class="text-center">{{ mfaText }}</h5>
        <div>
            <TwoFactorForm
                :allow-remember="allowRemember"
                :submit-route="store.url()"
            />
        </div>
    </div>
</template>
