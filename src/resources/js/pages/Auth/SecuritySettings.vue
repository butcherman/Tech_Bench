<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import SecuritySettingsForm from "@/features/auth/forms/SecuritySettingsForm.vue";

defineProps<{
    autoLogoutTimer: number;
    twoFa: MultiFactorConfig;
    oath: OathConfig;
    roleList: UserRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card>
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="cog" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">User Security Settings</h3>
                        <p class="text-muted">
                            Modify Multifactor Authentication and Single Sign On
                            settings.
                        </p>
                    </div>
                </div>
            </template>
            <SecuritySettingsForm
                :auto-logout-timer="autoLogoutTimer"
                :twoFa="twoFa"
                :oath="oath"
                :role-list="roleList"
            />
        </Card>
    </div>
</template>
