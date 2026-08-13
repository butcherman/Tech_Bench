<script setup lang="ts">
import MenuList from "@/core/components/MenuList.vue";
import { computed, ref } from "vue";
import { logout } from "@/wayfinder/routes";
import { useUserState } from "../state/userState";
import { show as changePassword } from "@/wayfinder/routes/user/change-password";
import { show as showSettings } from "@/wayfinder/routes/user/user-settings";

const emit = defineEmits<{
    "update:open": [boolean];
}>();

const props = defineProps<{
    open: boolean;
}>();

const { authorizedUser } = useUserState();

const isOpen = computed({
    get: () => props.open,
    set: (value) => emit("update:open", value),
});

/**
 * Settings Menu
 */
const menuList = ref<MenuItem[]>([
    {
        label: "Settings",
        icon: "fa-cog",
        route: showSettings.url(),
    },
    {
        label: "Change Password",
        icon: "key",
        route: changePassword.url(),
    },
]);
</script>

<template>
    <div class="fixed top-13 right-5 bg-white">
        <Transition name="settings-menu" appear>
            <div v-if="isOpen" class="min-w-50 rounded-lg px-4 py-2">
                <h6
                    class="text-center border-b border-b-slate-200 text-muted pb-2 mb-2"
                >
                    {{ authorizedUser?.full_name }}
                </h6>
                <MenuList :menu-list="menuList" />
                <ul
                    class="flex flex-col gap-1 mt-2 me-2 border-t border-t-slate-200 w-full"
                >
                    <li class="mt-2">
                        <Link
                            :href="logout.url()"
                            as="div"
                            method="POST"
                            class="block p-2 w-full h-full hover:bg-slate-100 rounded-lg text-slate-700 pointer"
                        >
                            <fa-icon icon="sign-out-alt" />
                            Logout
                        </Link>
                    </li>
                </ul>
            </div>
        </Transition>
    </div>
</template>
