<template>
    <div>
        <Head title="User Settings" />
        <div class="row">
            <div class="col-md-6 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Account</div>
                        <UserAccountForm :current-user="current_user" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 stretch-card">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <div class="card-title">Notifications</div>
                        notifications form
                    </div>
                </div>
            </div>
        </div>
        <div v-if="twoFaEnabled" class="row justify-content-center mt-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">My Devices</div>
                        <p class="text-center">
                            The following devices have been verified and saved
                            as trusted devices.
                        </p>
                        <p class="text-center">
                            A trusted device allows you to skip the two-factor
                            authentication for 180 days from the registration
                            date.
                        </p>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Device Type</th>
                                        <th>Device OS</th>
                                        <th>Browser Used</th>
                                        <th>Last Successful Login</th>
                                        <th>Registration Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="device in devices"
                                        :key="device.device_id"
                                    >
                                        <td>{{ upperFirst(device.type) }}</td>
                                        <td>{{ device.os }}</td>
                                        <td>{{ device.browser }}</td>
                                        <td
                                            class="pointer"
                                            :title="`From IP Address ${device.updated_ip_address}`"
                                            v-tooltip
                                        >
                                            {{ device.updated_at }}
                                        </td>
                                        <td
                                            class="pointer"
                                            :title="`From IP Address ${device.registered_ip_address}`"
                                            v-tooltip
                                        >
                                            {{ device.created_at }}
                                        </td>
                                        <td>
                                            <span
                                                class="text-danger pointer"
                                                title="Remove This Device"
                                                v-tooltip
                                                @click="removeDevice(device)"
                                            >
                                                <fa-icon icon="xmark" />
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserAccountForm from "@/Forms/User/UserAccountForm.vue";
import verifyModal from "@/Modules/verifyModal";
import { upperFirst } from "lodash";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    current_user: user;
    twoFaEnabled: boolean;
    devices: userDevice[];
}>();

const removeDevice = (device: userDevice) => {
    verifyModal(
        `If this device is removed, you will be required to enter a Verification
        Code if you use it to log in again.`
    ).then((res) => {
        if (res) {
            router.get(
                route("user.remove-device", [
                    props.current_user.username,
                    device.device_id,
                ]),
                {
                    preserveScroll: true,
                }
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
