<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import UserSecuritySettingsForm from "@/features/user/forms/UserSecuritySettingsForm.vue";

defineOptions({ layout: AppLayout });

defineProps<{
    autoLogoutTimer: number;
    twoFa: MultiFactorConfig;
    oath: OathConfig;
    roleList: UserRole[];
}>();
</script>

<template>
    <div>
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1 class="text-black">User Security Settings</h1>
            <p class="text-muted">
                Modify Multifactor Authentication and Single Sign On settings.
            </p>
        </div>
        <div class="flex justify-center">
            <div class="w-full lg:w-3/4">
                <UserSecuritySettingsForm
                    :auto-logout-timer
                    :two-fa
                    :oath
                    :role-list
                />
            </div>
        </div>
    </div>
</template>
