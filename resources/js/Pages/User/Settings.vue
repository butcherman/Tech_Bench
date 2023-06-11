<template>
    <Head title="User Settings" />
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Account</div>
                    <UserAccountForm :user="app.user" />
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <div class="card-title">Notifications</div>
                    notification settings
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserAccountForm from "@/Forms/User/UserAccountForm.vue";

defineProps<{
    app: appProps;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
