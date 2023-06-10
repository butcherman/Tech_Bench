<template>
    <LoginForm />
    <div class="form-group row justify-content-center mb-0">
            <div  class="col-md-8 text-center">
                <a
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
import LoginForm from '@/Forms/Auth/LoginForm.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
// import { ref, reactive, onMounted } from 'vue';

// const props = defineProps<{}>();

const $route = route;
</script>

<script lang="ts">
export default { layout: AuthLayout, components: { LoginForm } };
</script>
