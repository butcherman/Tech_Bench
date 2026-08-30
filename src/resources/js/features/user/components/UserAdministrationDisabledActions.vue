<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import verifyModal from "@/core/features/verifyModal/index.js";
import { restore } from "@/wayfinder/routes/admin/user";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    user: User;
}>();

const onEnableUser = () => {
    verifyModal(
        "User will be enabled.  Recommend resetting users password",
    ).then((res) => {
        if (res) {
            router.get(restore.url(props.user.username));
        }
    });
};
</script>

<template>
    <Card>
        <div class="flex flex-col gap-5">
            <div class="flex flex-col items-center">
                <h3 class="text-muted w-full">User Actions</h3>
                <BaseButton
                    text="Enable User"
                    variant="warning"
                    class="w-full lg:w-3/4"
                    icon="user"
                    @click="onEnableUser"
                />
            </div>
        </div>
    </Card>
</template>
