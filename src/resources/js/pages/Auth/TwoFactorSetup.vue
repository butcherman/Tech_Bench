<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import { useAppData } from "@/core/state/appData";
import { useTwoFactorMethodHelper } from "@/features/auth/composables/twoFactorMethodHelper";

const props = defineProps<{
    required: boolean;
    methods: MultiFactorMethod[];
}>();

const { appName } = useAppData();
const { getMethodSetupText, getMethodSetupUrl } = useTwoFactorMethodHelper();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card>
            <h4 class="text-center">Secure Your Account</h4>
            <p class="text-center">
                <span v-if="required">
                    Multi-Factor Authentication is required to use the
                    {{ appName }}.
                </span>
                Please select one of the options below to complete the MFA Setup
                Process.
            </p>
            <div class="my-4 flex flex-col gap-2 items-center">
                <BaseButton
                    v-for="method in methods"
                    :href="getMethodSetupUrl(method)"
                    :key="method"
                    :text="getMethodSetupText(method)"
                    class="w-3/4 md:w-1/2"
                />
            </div>
        </Card>
    </div>
</template>
