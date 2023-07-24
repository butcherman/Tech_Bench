<template>
    <div>
        <h5 class="text-center">Setup Complete!</h5>
        <p class="text-center">
            You have completed the Wizard and are ready to get going. Use any
            one of the links below to start using the Tech Bench.
        </p>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <Link :href="$route('dashboard')" class="btn btn-info w-100">Go To Dashboard</Link>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 my-2">
                <Link :href="$route('admin.users.create')" class="btn btn-info w-100">Create New User</Link>
            </div>
            <div class="col-md-4 my-2">
                <Link :href="$route('admin.user-roles.index')" class="btn btn-info w-100">View/Modify User Roles</Link>
            </div>
            <div class="col-md-4 my-2">
                <Link :href="$route('admin.user-settings.get')" class="btn btn-info w-100">See User Settings and Login Option</Link>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 my-2">
                <Link :href="$route('equipment.index')" class="btn btn-info w-100">Create New Equipment</Link>
            </div>
            <div class="col-md-4 my-2">
                <Link :href="$route('admin.logo.get')" class="btn btn-info w-100">Change Tech Bench Logo</Link>
            </div>
            <div class="col-md-4 my-2">
                <Link :href="$route('admin.security.index')" class="btn btn-info w-100">Configure SSL Certificate</Link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
