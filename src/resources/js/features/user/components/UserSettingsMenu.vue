<script setup lang="ts">
import { useUserAuth } from "@/core/state/userAuth";
import { computed, ref } from "vue";
import { show as changePassword } from "@/wayfinder/routes/user/change-password";
import { show as showSettings } from "@/wayfinder/routes/user/user-settings";
import { logout } from "@/wayfinder/routes";

const emit = defineEmits(["update:modelValue"]);

const props = defineProps<{
    modelValue: boolean;
}>();

/**
 * Status of the Menu - opened or closed
 */
const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const { user } = useUserAuth();

const menuList = ref<menuItem[]>([
    {
        label: "Settings",
        icon: "fa-cog",
        route: changePassword.url(),
    },
    {
        label: "Change Password",
        icon: "key",
        route: showSettings.url(),
    },
]);
</script>

<template>
    <Transition name="settings-menu" appear>
        <div v-if="isOpen" class="min-w-50 rounded-lg px-4 py-2">
            <h6
                class="text-center border-b border-b-slate-200 text-muted pb-2 mb-2"
            >
                {{ user.full_name }}
            </h6>
            <ul>
                <li
                    v-for="item in menuList"
                    :key="item.label"
                    class="py-2 px-2 hover:bg-slate-100 rounded-md"
                >
                    <Link :href="item.route">
                        <fa-icon :icon="item.icon" />
                        {{ item.label }}
                    </Link>
                </li>
                <li class="border-t border-t-slate-200">
                    <Link
                        :href="logout.url()"
                        method="POST"
                        class="w-full block py-2 px-4 pointer text-start hover:bg-slate-100 rounded-md"
                    >
                        <fa-icon icon="sign-out-alt" />
                        Logout
                    </Link>
                </li>
            </ul>
        </div>
    </Transition>
</template>

<style scoped>
.settings-menu-enter-active,
.settings-menu-leave-active {
    transition: opacity 250ms ease;
}

.settings-menu-enter-from,
.settings-menu-leave-to {
    opacity: 0;
}
</style>
