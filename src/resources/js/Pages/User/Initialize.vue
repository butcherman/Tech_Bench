<template>
    <div class="row justify-content-center align-items-center h-100 p-3">
        <Head title="Forgot Password" />
        <div class="col-md-6">
            <div class="card">
                <div class="card-body h-100">
                    <img
                        :src="app.logo"
                        class="img-fluid w-50 ms-auto me-auto"
                    />
                    <h5 class="text-center">
                        Welcome to the {{ app.name }}, {{ user.full_name }}
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
                        </div>
                    </div>
                    <p class="text-center">
                        Please create a secure password to finish setting up
                        your account
                    </p>
                    <ResetPasswordForm
                        :email="user.email"
                        :token="token"
                        initialize
                    />
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Password Rules:</h5>
                    <ul>
                        <li v-for="rule in rules" class="mx-2">
                            {{ rule }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import ResetPasswordForm from "@/Forms/Auth/ResetPasswordForm.vue";
import { useAppStore } from "@/Store/AppStore";

const props = defineProps<{
    token: string;
    user: user;
    rules: string[];
}>();
const app = useAppStore();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
