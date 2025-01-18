<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <Card title="My Account">
            <UserAccountForm :user="current_user" />
        </Card>
        <Card title="My Settings">
            <UserSettingsForm :user="current_user" :settings="settings" />
        </Card>
        <Card v-if="allowSaveDevice" title="My Devices" class="md:col-span-2">
            <p class="text-center">
                The following devices have been verified and saved as trusted
                devices.
            </p>
            <p class="text-center">
                A trusted device allows you to skip the two-factor
                authentication for 180 days from the registration date.
            </p>
            <hr class="mb-2" />
            <DataTable
                ref="deviceTable"
                :columns="tableHeaders"
                :rows="devices"
                sync-loading-state
            >
                <template #row.action="{ rowData }">
                    <DeleteBadge
                        v-tooltip.left="'Delete Device'"
                        confirm-msg="Are you sure you want to remove this device?"
                        confirm
                        @accepted="deleteDevice(rowData)"
                    />
                </template>
            </DataTable>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import UserAccountForm from "@/Forms/User/UserAccountForm.vue";
import UserSettingsForm from "@/Forms/User/UserSettingsForm.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps<{
    current_user: user;
    allowSaveDevice: boolean;
    devices: userDevice[];
    settings: userSettings[];
}>();

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

<script lang="ts">
export default { layout: AppLayout };
</script>
