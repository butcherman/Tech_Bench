<template>
    <Head title="Login" />
    <LoginForm />
    <div class="form-group row justify-content-center mb-0">
        <div class="col-md-8 text-center">
            <a
                v-if="allow_oath"
                class="btn btn-link text-muted"
                :href="$route('azure-login')"
            >
                Login with Office 365
            </a>
        </div>
        <div class="col-md-8 text-center">
            <Link
                class="btn btn-link text-muted"
                :href="$route('password.forgot')"
            >
                Forgot Your Password?
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
import LoginForm from "@/Forms/Auth/LoginForm.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";

defineProps<{
    allow_oath: boolean;
}>();
</script>

<script lang="ts">
export default { layout: [GuestLayout, AuthLayout] };
</script>
