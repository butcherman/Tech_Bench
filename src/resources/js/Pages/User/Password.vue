<template>
    <div class="row justify-content-center align-self-center h-100 w-100">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <UserPasswordForm />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserPasswordForm from "@/Forms/User/UserPasswordForm.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
