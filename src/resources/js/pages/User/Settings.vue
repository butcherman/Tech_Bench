<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import TwoFactorDevices from "@/features/user/components/TwoFactorDevices.vue";
import TwoFactorSettings from "@/features/user/components/TwoFactorSettings.vue";
import UserSecurity from "@/features/user/components/UserSecurity.vue";
import UserSettingsPreferences from "@/features/user/components/UserSettingsPreferences.vue";
import UserSettingsProfile from "@/features/user/components/UserSettingsProfile.vue";
import { computed, ref } from "vue";
import { useAppData } from "@/core/state/appData";
import { useAnimationHelper } from "@/core/composables/animationHelper";

interface SettingsMenuItem {
    text: string;
    component: string;
    enabled?: boolean;
}

const props = defineProps<{
    current_user: User;
    passwordRules: string[];
    settings: UserSettings[];
    twoFa: {
        allowSaveDevice: boolean;
        allowAuthenticator: boolean;
        allowEmail: boolean;
        currentVia: "email" | "authenticator" | null;
        devices: UserDevice[];
        enabled: boolean;
    };
}>();

const { appName } = useAppData();
const { fadeIn, fadeOut, beforeFadeIn } = useAnimationHelper();

const activeComponentName = ref("profile");

const activeComponent = computed(() => {
    return {
        profile: UserSettingsProfile,
        preferences: UserSettingsPreferences,
        security: UserSecurity,
        TwoFaSettings: TwoFactorSettings,
        TwoFaDevices: TwoFactorDevices,
    }[activeComponentName.value];
});

const activeTitle = computed(() => {
    return {
        profile: "My Profile",
        preferences: "My Preferences",
        security: "Security",
        TwoFaSettings: "2Factor Settings",
        TwoFaDevices: "My Trusted Devices",
    }[activeComponentName.value];
});

const onMenuClick = (menuItem: SettingsMenuItem) => {
    activeComponentName.value = menuItem.component;
};

/*
|-------------------------------------------------------------------------------
| Settings Menu Data
|-------------------------------------------------------------------------------
*/
const accountMenu: SettingsMenuItem[] = [
    {
        text: "Profile",
        component: "profile",
    },
    {
        text: "Preferences",
        component: "preferences",
    },
];

const securityMenu: SettingsMenuItem[] = [
    {
        text: "Security",
        component: "security",
        enabled: true,
    },
    {
        text: "MFA",
        component: "TwoFaSettings",
        enabled: props.twoFa.enabled,
    },
    {
        text: "Devices",
        component: "TwoFaDevices",
        enabled: props.twoFa.enabled && props.twoFa.allowSaveDevice,
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col gap-3">
        <Card>
            <h3 class="text-center">User Settings</h3>
            <p class="text-center">
                Manage your acocunt, security and {{ appName }} preferences.
            </p>
        </Card>
        <div class="flex flex-col md:flex-row gap-3">
            <Card class="h-auto md:min-h-100 flex-1">
                <div class="flex flex-col gap-7 p-5">
                    <div>
                        <h5 class="text-muted">ACCOUNT</h5>
                        <ul class="ms-5">
                            <li
                                v-for="item in accountMenu"
                                class="pointer hover:font-semibold"
                                :class="{
                                    'font-bold':
                                        item.component === activeComponentName,
                                }"
                                :key="item.text"
                                @click="onMenuClick(item)"
                            >
                                {{ item.text }}
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-muted">SECURITY</h5>
                        <ul class="ms-5">
                            <template
                                v-for="item in securityMenu"
                                :key="item.text"
                            >
                                <li
                                    v-if="item.enabled"
                                    class="pointer hover:font-semibold"
                                    :class="{
                                        'font-bold':
                                            item.component ===
                                            activeComponentName,
                                    }"
                                    @click="onMenuClick(item)"
                                >
                                    {{ item.text }}
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </Card>
            <Card :title="activeTitle">
                <Transition
                    mode="out-in"
                    :css="false"
                    @before-enter="beforeFadeIn"
                    @enter="fadeIn"
                    @leave="fadeOut"
                >
                    <div class="h-full">
                        <component :is="activeComponent" v-bind="props" />
                    </div>
                </Transition>
            </Card>
        </div>
    </div>
</template>
