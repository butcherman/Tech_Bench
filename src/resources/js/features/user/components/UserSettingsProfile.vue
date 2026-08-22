<script setup lang="ts">
import UserAvatar from "./UserAvatar.vue";
import UserProfileForm from "../forms/UserProfileForm.vue";

const props = defineProps<{
    current_user: User;
}>();
</script>

<template>
    <div>
        <div class="flex flex-col gap-3 p-5">
            <div class="flex gap-3">
                <div>
                    <UserAvatar size="lg" :user="current_user" />
                </div>
                <div class="flex flex-col justify-center">
                    <div>{{ current_user.full_name }}</div>
                    <div>{{ current_user.email }}</div>
                </div>
            </div>
            <div class="flex-1">
                <UserProfileForm :user="current_user" />
            </div>
        </div>
    </div>
</template>
