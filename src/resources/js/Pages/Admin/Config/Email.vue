<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import EmailConfigForm from "@/Forms/Admin/Config/EmailConfigForm.vue";
import { getVariantClass } from "@/Composables/styleData.module";

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
        <Card class="tb-card" title="Email Settings">
            <div>
                <EmailConfigForm :settings="settings" />
                <div class="text-center mt-2">
                    <Link
                        as="button"
                        :href="$route('admin.test-email')"
                        class="w-3/4 rounded-lg px-3 py-2"
                        :class="getVariantClass('help')"
                        preserve-state
                    >
                        Send Test Email (uses saved values)
                    </Link>
                </div>
            </div>
        </Card>
    </div>
</template>
