<template>
    <Head title="Update User" />
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Edit User</div>
                    <UserForm :user="user" :roles="roles" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserForm from "@/Forms/Admin/User/UserForm.vue";

defineProps<{
    user: user;
    roles: userRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout, components: { UserForm } };
</script>
