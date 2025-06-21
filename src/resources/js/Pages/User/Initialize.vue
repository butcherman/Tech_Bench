<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import LogoImage from "@/Components/LogoImage.vue";
import ResetUserPasswordForm from "@/Forms/Admin/User/ResetUserPasswordForm.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

defineProps<{
    token: string;
    user: user;
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex items-center justify-center h-screen mx-1 py-2">
        <div>
            <LogoImage>
                <div class="w-full text-center">
                    <h2>Welcome to the Tech Bench</h2>
                    <p>
                        Create a new password to finish setting up your account.
                    </p>
                    <ResetUserPasswordForm :user="user" :token="token" />
                    <ResourceList :list="rules" class="mt-3" />
                </div>
            </LogoImage>
        </div>
    </div>
</template>
