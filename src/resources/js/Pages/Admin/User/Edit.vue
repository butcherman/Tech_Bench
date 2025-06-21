<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import UserForm from "@/Forms/Admin/User/UserForm.vue";

defineProps<{
    roles: userRole[];
    user: user;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <UserForm :roles="roles" :user="user" />
        </Card>
    </div>
</template>
