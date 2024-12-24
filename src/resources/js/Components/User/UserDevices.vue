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
        <v-data-table :items="devices" :headers="headers" hide-default-footer>
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
const props = defineProps<{
    user: user;
    devices: userDevice[];
}>();

const removeDevice = (device: userDevice) => {
    console.log("remove device");
    console.log(device);
};

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
