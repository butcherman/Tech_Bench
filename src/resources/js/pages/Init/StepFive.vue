<script setup lang="ts">
import InitLayout from "@/layouts/InitLayout.vue";
import { useSetupState } from "@/features/init/state/setupState";

const { stepList, markStepComplete } = useSetupState();

const props = defineProps<{
    applicationSettings: {
        url: string;
        timezone: string;
        max_filesize: number;
        company_name: string;
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
    security: PasswordPolicy;
    admin: User;
}>();

console.log(stepList.value);
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div class="flex justify-center">
        <h1>Step 5</h1>
        <div>
            <pre>
                {{ props }}
            </pre>
        </div>
    </div>
</template>
