<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import Card from "@/core/components/Card.vue";
import verifyModal from "@/core/features/verifyModal";
import { computed } from "vue";
import { reset } from "@/wayfinder/routes/admin/user/two-factor";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    user: User;
    allowTwoFa: boolean;
    allowSaveDevice: boolean;
    savedDevicesCount: number;
}>();

const isActive = computed(() =>
    props.user.two_factor_confirmed_at ? true : false,
);

const activeText = computed(() => (isActive.value ? "Active" : "Disabled"));
const activeColor = computed(() =>
    isActive.value ? "text-success" : "text-danger",
);

const onResetMfa = () => {
    verifyModal("This will remove all MFA Settings and Saved Devices").then(
        (res) => {
            if (res) {
                router.put(reset.url(props.user.username));
            }
        },
    );
};
</script>

<template>
    <Card title="Security">
        <template #append-title>
            <BaseBadge
                text="Reset MFA"
                size="sm"
                class="mt-3"
                variant="warning"
                @click="onResetMfa"
            />
        </template>
        <table>
            <tbody>
                <tr>
                    <td class="pe-15 text-muted">2FA</td>
                    <td>
                        <fa-icon icon="circle" :class="activeColor" />
                        {{ activeText }}
                    </td>
                </tr>
                <tr>
                    <td class="pe-15 text-muted">2FA Method</td>
                    <td>{{ user.two_factor_via ?? "none" }}</td>
                </tr>
                <tr v-if="allowSaveDevice">
                    <td class="pe-15 text-muted">Saved Devices</td>
                    <td>{{ savedDevicesCount }}</td>
                </tr>
            </tbody>
        </table>
    </Card>
</template>
