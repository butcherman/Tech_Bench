<template>
    <Head title="New User" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Enter New User Information</div>
                        <UserForm :roles="roles" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import UserForm from "@/Forms/Admin/User/UserForm.vue";

defineProps<{
    roles: userRole[];
}>();
</script>

<script lang="ts">
export default { layout: App };
</script>
