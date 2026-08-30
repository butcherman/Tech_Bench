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
