<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">User Permissions Report</div>
                    <UserListReportsForm
                        :user-list="userList"
                        submit-route="reports.user.run-permissions"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserListReportsForm from "@/Forms/Report/User/UserListReportsForm.vue";

defineProps<{
    userList: user[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
