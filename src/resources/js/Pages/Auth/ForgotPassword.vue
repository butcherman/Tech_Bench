<template>
    <div class="row justify-content-center align-items-center p-3 h-100">
        <Head title="Forgot Password" />
        <div class="col-sm-8 col-md-6 col-xl-5 col-xxl-4">
            <div class="card">
                <div class="card-body h-100 text-center">
                    <img :src="app.logo" class="img-fluid w-50 mx-auto" />
                    <h5>
                        Enter your Email Address for instructions on recovering
                        your account
                    </h5>
                    <div class="row align-items-center">
                        <div class="col">
                            <div v-if="app.flash.length">
                                <div
                                    v-for="flash in app.flash"
                                    class="alert text-center"
                                    :class="`alert-${flash.type}`"
                                >
                                    {{ flash.message }}
                                </div>
                            </div>
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
