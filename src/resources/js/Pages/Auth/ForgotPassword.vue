<template>
    <div class="row justify-content-center align-items-center h-100 p-3">
        <Head title="Forgot Password" />
        <div class="col-md-6">
            <div class="card">
                <div class="card-body h-100 text-center">
                    <img :src="app.logo" class="img-fluid w-50" />
                    <h5>
                        Enter your Email Address for instructions on recovering
                        your account
                    </h5>
                    <div class="row align-items-center">
                        <div class="col">
                            <ForgotPasswordForm />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import ForgotPasswordForm from "@/Forms/Auth/ForgotPasswordForm.vue";
import { useAppStore } from "@/Store/AppStore";

const app = useAppStore();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
