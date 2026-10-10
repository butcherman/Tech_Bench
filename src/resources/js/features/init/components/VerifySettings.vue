<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import EditButton from "@/core/components/buttons/EditButton.vue";
import { computed } from "vue";
import { step1, step2, step3, step4, finish } from "@/wayfinder/routes/init";

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

const mfaStatus = computed(() => {
    if (props.security.twoFa.required) {
        return "Required";
    }

    if (props.security.twoFa.enabled) {
        return "Enabled";
    }

    return "Disabled";
});
</script>

<template>
    <div class="w-full p-3">
        <div>
            <h3 class="border-b border-b-slate-300 pb-2">Application</h3>
            <div class="grid grid-cols-2">
                <div>Company Name</div>
                <div>{{ applicationSettings.company_name }}</div>
                <div>URL</div>
                <div>{{ applicationSettings.url }}</div>
                <div>Timezone</div>
                <div>{{ applicationSettings.timezone }}</div>
                <div>Welcome Message</div>
                <div>{{ applicationSettings.welcome_message }}</div>
            </div>
            <div class="flex flex-row-reverse">
                <EditButton :href="step1.url()" class="w-1/4" size="sm" />
            </div>
        </div>
        <div>
            <h3 class="border-b border-b-slate-300 p-2">Email</h3>
            <div class="grid grid-cols-2">
                <div>SMTP Server</div>
                <div>{{ emailSettings.host }}</div>
                <div>Port</div>
                <div>{{ emailSettings.port }}</div>
                <div>Encryption</div>
                <div>{{ emailSettings.encryption }}</div>
                <div>From Address</div>
                <div>{{ emailSettings.from_address }}</div>
                <template v-if="emailSettings.require_auth">
                    <div>Username</div>
                    <div>{{ emailSettings.username }}</div>
                    <div>Password</div>
                    <div>{{ emailSettings.password }}</div>
                </template>
            </div>
            <div class="flex flex-row-reverse">
                <EditButton :href="step2.url()" class="w-1/4" size="sm" />
            </div>
        </div>
        <div>
            <h3 class="border-b border-b-slate-300 p-2">Security</h3>
            <div class="grid grid-cols-2">
                <div>Minimum Password Length</div>
                <div>{{ security.password.min_length }}</div>
                <div>Password Lifetime</div>
                <div v-if="security.password.expire === 0">Unlimited</div>
                <div v-else>{{ security.password.expire }} days</div>
                <div>MFA</div>
                <div>{{ mfaStatus }}</div>
            </div>
            <div class="flex flex-row-reverse">
                <EditButton :href="step3.url()" class="w-1/4" size="sm" />
            </div>
        </div>
        <div>
            <h3 class="border-b border-b-slate-300 p-2">Administrator</h3>
            <div class="grid grid-cols-2">
                <div>Name</div>
                <div>{{ admin.first_name }} {{ admin.last_name }}</div>
                <div>Email Address</div>
                <div>{{ admin.email }}</div>
            </div>
            <div class="flex flex-row-reverse">
                <EditButton :href="step4.url()" class="w-1/4" size="sm" />
            </div>
        </div>
        <div class="mt-4 flex justify-center">
            <BaseButton
                :href="finish.url()"
                text="Complete Setup"
                class="w-3/4"
            />
        </div>
    </div>
</template>
