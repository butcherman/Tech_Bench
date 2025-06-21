<script setup lang="ts">
import { Avatar, Menu } from "primevue";
import { ref, useTemplateRef } from "vue";
import { useAuthStore } from "@/Stores/AuthStore";

interface menuItem {
    label: string;
    icon: string;
    route: string;
}

const auth = useAuthStore();
const userMenu = useTemplateRef("user-menu");
const menuList = ref<menuItem[]>([
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
            v-tooltip.left="'Account Information'"
            @click="userMenu?.toggle"
        >
            <Avatar
                :label="auth.user.initials"
                shape="circle"
                size="large"
                class="bg-blue-200"
            />
        </button>
        <Menu ref="user-menu" :model="menuList" popup>
            <template #start>
                <div class="text-center font-semibold border-b my-2 p-2">
                    {{ auth.user.full_name }}
                </div>
            </template>
            <template #item="{ item }">
                <div>
                    <Link :href="item.route" class="w-full block p-2">
                        <fa-icon :icon="item.icon" />
                        {{ item.label }}
                    </Link>
                </div>
            </template>
            <template #end>
                <div class="border-t my-2">
                    <Link
                        :href="$route('logout')"
                        method="POST"
                        class="w-full block py-2 px-4 pointer text-start hover:bg-slate-100"
                    >
                        <fa-icon icon="sign-out-alt" />
                        Logout
                    </Link>
                </div>
            </template>
        </Menu>
    </div>
</template>
