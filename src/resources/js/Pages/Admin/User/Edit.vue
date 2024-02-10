<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Edit User Details</div>
                    <UserForm :roles="roles" :user="user" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserForm from "@/Forms/Admin/User/UserForm.vue";

defineProps<{
    roles: userRole[];
    user: user;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
