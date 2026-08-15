<script setup lang="ts">
import DataTable from "@/core/features/dataResources/DataTable.vue";
import DeleteBadge from "@/core/components/badges/DeleteBadge.vue";
import { removeDevice } from "@/wayfinder/routes/user";
import { router } from "@inertiajs/vue3";
import { useColumnBuilder } from "@/core/features/dataResources/composables/columnBuilder";

const props = defineProps<{
    user: User;
    devices: UserDevice[];
}>();

const colHelper = useColumnBuilder<UserDevice>();

const tableColumns = [
    colHelper.text("type", "Device Type"),
    colHelper.text("os", "Device OS", {
        filterSelect: true,
    }),
    colHelper.text("browser", "Browser Used", {
        filterSelect: true,
    }),
    colHelper.text("updated_at", "Last Successful Login"),
    colHelper.text("created_at", "Registration Date"),
];

const onDeleteDevice = (device: UserDevice) => {
    console.log("delete device", device);
    router.delete(removeDevice([props.user.username, device.device_id]), {
        preserveScroll: true,
    });
};
</script>

<template>
    <div>
        <p class="text-center">
            The following devices have been verified and saved as trusted
            devices. This will allow you to skip 2FA on these devices for 180
            days from the registration date.
        </p>
        <DataTable :columns="tableColumns" :data="devices" actions-slot>
            <template #row.actions="{ rowData }">
                <DeleteBadge
                    v-tooltip.left="'Delete Device'"
                    confirm
                    @yes-clicked="onDeleteDevice(rowData)"
                />
            </template>
        </DataTable>
    </div>
</template>
