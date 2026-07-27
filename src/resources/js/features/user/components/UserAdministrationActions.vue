<script setup lang="ts">
import AdminResetUserPasswordForm from "../forms/AdminResetUserPasswordForm.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import BaseModal from "@/core/components/modals/BaseModal.vue";
import { ref } from "vue";
import { edit } from "@/wayfinder/routes/admin/user/index.js";
import { useUserAdministrationHelper } from "../composables/userAdministrationHelper.js";

const props = defineProps<{
    user: User;
    allowTwoFa: boolean;
    allowSaveDevice: boolean;
}>();

const { resetTwoFa, sendResetEmail, disableUser, restoreUser } =
    useUserAdministrationHelper();

/**
 * Modal to reset a users password
 */
const openResetModal = ref(false);
</script>

<template>
    <div class="flex flex-col justify-center h-full gap-2">
        <template v-if="user.deleted_at">
            <BaseButton
                text="Enable User"
                variant="error"
                @click="restoreUser(user)"
            />
        </template>
        <template v-else>
            <BaseButton
                text="Reset Users Password"
                variant="warning"
                icon="key"
                @click="openResetModal = true"
            />
            <BaseButton
                v-if="allowTwoFa"
                text="Reset 2FA Settings"
                variant="info"
                icon="mobile-screen"
                @click="resetTwoFa(user, allowSaveDevice)"
            />
            <BaseButton
                text="Send Reset Password Link"
                variant="help"
                icon="envelope"
                @click="sendResetEmail(user)"
            />
            <BaseButton
                text="Update User Information"
                variant="info"
                icon="pencil"
                :href="edit.url(user.username)"
            />
            <BaseButton
                text="Disable User"
                variant="danger"
                icon="trash-alt"
                @click="disableUser(user)"
            />
        </template>
        <BaseModal v-model:open="openResetModal" title="Reset Password">
            <AdminResetUserPasswordForm
                :user="user"
                @success="openResetModal = false"
            />
        </BaseModal>
    </div>
</template>
