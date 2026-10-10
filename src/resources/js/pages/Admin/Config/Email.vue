<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import EmailConfigForm from "@/features/administration/forms/EmailConfigForm.vue";
import { testEmail } from "@/wayfinder/routes/admin";

defineProps<{
    settings: {
        from_address: string;
        host: string;
        port: number;
        encryption: string;
        require_auth: boolean;
        username: string;
        password: string;
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card size="md" title="Email Settings">
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="fa-envelope" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">Email Settings</h3>
                        <p class="text-muted">
                            Modify SMTP settings to allow users to receive
                            emails from the application.
                        </p>
                    </div>
                </div>
            </template>
            <div>
                <EmailConfigForm :settings />
                <div class="text-center mt-2">
                    <BaseButton
                        :href="testEmail.url()"
                        class="w-3/4 rounded-lg px-3 py-2"
                        variant="help"
                    >
                        Send Test Email (uses saved values)
                    </BaseButton>
                </div>
            </div>
        </Card>
    </div>
</template>
