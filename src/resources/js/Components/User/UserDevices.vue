<template>
    <div>
        <p class="text-center">
            The following devices have been verified and saved as trusted
            devices.
        </p>
        <p class="text-center">
            A trusted device allows you to skip the two-factor authentication
            for 180 days from the registration date.
        </p>
        <hr class="mb-2" />
        <v-data-table
            :items="devices"
            :headers="headers"
            :loading="isLoading"
            hide-default-footer
        >
            <template #item.action="{ item }">
                <span
                    class="text-danger pointer"
                    v-tooltip="'Remove Device'"
                    @click="removeDevice(item)"
                >
                    <font-awesome-icon icon="xmark" />
                </span>
            </template>
        </v-data-table>
    </div>
</template>

<script setup lang="ts">
import VerifyModal from "@/Modules/VerifyModal";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    user: user;
    devices: userDevice[];
}>();

const isLoading = ref(false);

/**
 * Remove a saved device.
 */
const removeDevice = (device: userDevice): void => {
    VerifyModal().then((res) => {
        if (res) {
            isLoading.value = true;
            router.delete(
                route("user.remove-device", [
                    props.user.username,
                    device.device_id,
                ]),
                {
                    preserveScroll: true,
                    onFinish: () => (isLoading.value = false),
                }
            );
        }
    });
};

/**
 * Headers for Data Table
 */
const headers = [
    {
        title: "Device Type",
        value: "type",
    },
    {
        title: "Device OS",
        value: "os",
    },
    {
        title: "Browser Used",
        value: "browser",
    },
    {
        title: "Last Successful Login",
        value: "updated_at",
    },
    {
        title: "Registration Date",
        value: "created_at",
    },
    {
        value: "action",
    },
];
</script>
