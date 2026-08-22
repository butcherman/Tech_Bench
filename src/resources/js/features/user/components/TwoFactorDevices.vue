<script setup lang="ts">
import DeleteBadge from "@/core/components/badges/DeleteBadge.vue";
import { removeDevice } from "@/wayfinder/routes/user";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    current_user: User;
    twoFa: {
        allowSaveDevice: boolean;
        allowAuthenticator: boolean;
        allowEmail: boolean;
        currentVia: "email" | "authenticator" | null;
        devices: UserDevice[];
        enabled: boolean;
    };
}>();

const getDeviceIcon = (device: UserDevice) => {
    switch (device.type) {
        case "mobile":
        case "smartphone":
            return "mobile-screen";
        case "desktop":
            return "computer";
        default:
            return "tv";
    }
};

const onDeleteDevice = (device: UserDevice) => {
    router.delete(
        removeDevice([props.current_user.username, device.device_id]),
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <div>
        <p class="text-center">
            The following devices have been verified and saved as trusted
            devices. This will allow you to skip 2FA on these devices for 180
            days from the registration date.
        </p>
        <div class="flex flex-col gap-2">
            <div
                v-for="device in twoFa.devices"
                class="border border-slate-300 rounded-lg p-2"
            >
                <div>
                    <fa-icon :icon="getDeviceIcon(device)" />
                    {{ device.type }}
                </div>
                <div class="mb-2">{{ device.browser }} - {{ device.os }}</div>
                <div>Created: {{ device.created_at }}</div>
                <div>Last Used: {{ device.updated_at }}</div>
                <div class="flex flex-row-reverse">
                    <DeleteBadge
                        v-tooltip.left="'Delete Device'"
                        confirm
                        @yes-clicked="onDeleteDevice(device)"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
