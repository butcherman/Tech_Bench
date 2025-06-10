<script setup lang="ts">
import DataTable from "../_Base/DataTable/DataTable.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    current_user: user;
    devices: userDevice[];
}>();

/**
 * Delete a saved device from the 2FA device list.
 */
const deleteDevice = (deviceData: userDevice) => {
    router.delete(
        route("user.remove-device", [
            props.current_user.username,
            deviceData.device_id,
        ]),
        {
            preserveScroll: true,
        }
    );
};

const tableHeaders = [
    {
        label: "Device Type",
        field: "type",
    },
    {
        label: "Device OS",
        field: "os",
    },
    {
        label: "Browser Used",
        field: "browser",
    },
    {
        label: "Last Successful Login",
        field: "updated_at",
    },
    {
        label: "Registration Date",
        field: "created_at",
    },
    {
        field: "action",
    },
];
</script>

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
        <DataTable :columns="tableHeaders" :rows="devices" sync-loading-state>
            <template #row.action="{ rowData }">
                <DeleteBadge
                    v-tooltip.left="'Delete Device'"
                    confirm-msg="Are you sure you want to remove this device?"
                    confirm
                    @accepted="deleteDevice(rowData)"
                />
            </template>
        </DataTable>
    </div>
</template>
