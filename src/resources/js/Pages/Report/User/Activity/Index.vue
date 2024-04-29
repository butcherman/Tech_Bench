<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">User Login Activity Report</div>
                    <UserActivityForm :user-list="userList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserActivityForm from "@/Forms/Report/User/UserActivityForm.vue";

defineProps<{
    userList: user[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
