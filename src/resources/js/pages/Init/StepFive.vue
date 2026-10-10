<script setup lang="ts">
import InitLayout from "@/layouts/InitLayout.vue";
import VerifySettings from "@/features/init/components/VerifySettings.vue";
import { useSetupState } from "@/features/init/state/setupState";

const { stepList } = useSetupState();

const props = defineProps<{
    applicationSettings: {
        url: string;
        timezone: string;
        max_filesize: number;
        company_name: string;
        welcome_message: string | null;
    };
    emailSettings: {
        from_address: string;
        host: string;
        port: number;
        encryption: string;
        require_auth: boolean;
        username: string;
        password: string;
    };
    security: {
        password: PasswordPolicy;
        twoFa: MultiFactorConfig;
    };
    admin: User;
}>();

console.log(stepList.value);
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div class="flex flex-col gap-4 justify-center items-center h-full">
        <h1 class="text-center">Review Your Configuration</h1>
        <VerifySettings v-bind="props" />
    </div>
</template>
