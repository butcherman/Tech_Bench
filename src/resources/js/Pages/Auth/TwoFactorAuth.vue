<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";
import TwoFactorForm from "@/Forms/Auth/TwoFactorForm.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";

defineProps<{
    allowRemember: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center align-middle">
        <Card class="tb-card" title="Two Factor Authentication">
            <h5 class="text-center">
                A verification code has been sent to your email address.
            </h5>
            <p class="text-center">Please input the code below.</p>
            <div>
                <TwoFactorForm :allow-remember="allowRemember" />
            </div>
            <div class="text-center mt-4">
                <BaseButton
                    :href="$route('dashboard')"
                    class="w-3/4"
                    variant="warning"
                >
                    Send New Verification Code
                </BaseButton>
            </div>
        </Card>
    </div>
</template>
