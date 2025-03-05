<script setup lang="ts">
import { Avatar, Menu } from "primevue";
import { ref } from "vue";
import { useAuthStore } from "@/Stores/AuthStore";

// const app = useAppStore();
const auth = useAuthStore();
const userMenu = ref<InstanceType<typeof Menu> | null>(null);
const menuList = ref([
    {
        label: "Settings",
        icon: "fa-cog",
        route: route("user.user-settings.show"),
    },
    {
        label: "Change Password",
        icon: "key",
        route: route("user.change-password.show"),
    },
]);
</script>

<template>
    <div>
        <button
            type="button"
            v-tooltip="'Account Information'"
            @click="userMenu?.toggle"
        >
            <Avatar
                :label="auth.user.initials"
                shape="circle"
                size="large"
                class="bg-blue-200"
            />
        </button>
        <Menu ref="userMenu" :model="menuList" popup>
            <template #start>
                <div class="text-center font-semibold border-b my-2 p-2">
                    {{ auth.user.full_name }}
                </div>
            </template>
            <template #item="{ item }">
                <div class="p-2">
                    <Link :href="item.route">
                        <fa-icon :icon="item.icon" />
                        {{ item.label }}
                    </Link>
                </div>
            </template>
            <template #end>
                <div class="border-t my-2 p-2">
                    <Link :href="$route('logout')" method="POST">
                        <fa-icon icon="sign-out-alt" />
                        Logout
                    </Link>
                </div>
            </template>
        </Menu>
    </div>
</template>
