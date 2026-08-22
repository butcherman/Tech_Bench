<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import Modal from "@/core/components/Modal.vue";
import UserAdministrationPasswordForm from "../forms/UserAdministrationPasswordForm.vue";
import verifyModal from "@/core/features/verifyModal/index.js";
import { edit } from "@/wayfinder/routes/admin/user";
import { passwordLink, destroy } from "@/wayfinder/routes/admin/user";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps<{
    user: User;
}>();

const showResetModal = ref(false);

const onSendResetLink = () => {
    router.post(passwordLink.url(), { email: props.user.email });
};

const onDisableUser = () => {
    verifyModal(`${props.user.full_name} will be immediately disabled`).then(
        (res) => {
            if (res) {
                router.delete(destroy.url(props.user.username));
            }
        },
    );
};
</script>

<template>
    <Card>
        <div class="flex flex-col gap-5">
            <div class="flex flex-col items-center">
                <h3 class="text-muted w-full">User Actions</h3>
                <BaseButton
                    text="Edit User"
                    variant="warning"
                    class="w-full lg:w-3/4"
                    :href="edit.url(user.username)"
                />
            </div>
            <div class="flex flex-col gap-2 items-center">
                <h3 class="text-muted w-full">Password</h3>
                <BaseButton
                    class="w-full lg:w-3/4"
                    text="Set New Password"
                    @click="showResetModal = true"
                />
                <BaseButton
                    class="w-full lg:w-3/4"
                    text="Send Reset Link"
                    @click="onSendResetLink"
                />
            </div>
            <div
                class="flex flex-col gap-2 items-center border-t border-t-slate-300 pt-3"
            >
                <BaseButton
                    class="w-full lg:w-3/4"
                    text="Disable User"
                    variant="danger"
                    @click="onDisableUser"
                />
            </div>
        </div>
        <Modal
            v-model:show="showResetModal"
            :title="`Reset password for ${user.full_name}`"
            size="sm"
        >
            <UserAdministrationPasswordForm
                :user="user"
                @success="showResetModal = false"
            />
        </Modal>
    </Card>
</template>
