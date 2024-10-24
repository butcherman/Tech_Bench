<template>
    <div>
        <Head title="User Settings" />
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">My Account</div>
                        <UserAccountForm
                            class="grow-form"
                            :user="current_user"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">My Settings</div>
                        <UserSettingsForm
                            class="grow-form"
                            :settings="settings"
                            :user="current_user"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="twoFaEnabled" class="row justify-content-center mt-2">
            <div class="col">
                <div class="card">
                    <div class="card-body h-100">
                        <div class="card-title">My Devices</div>
                        <UserDevices
                            :user="current_user"
                            :devices="devices"
                            class="h-100"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserAccountForm from "@/Forms/User/UserAccountForm.vue";
import UserSettingsForm from "@/Forms/User/UserSettingsForm.vue";
import UserDevices from "@/Components/User/UserDevices.vue";

defineProps<{
    current_user: user;
    twoFaEnabled: boolean;
    devices: userDevice[];
    settings: userSettings[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
