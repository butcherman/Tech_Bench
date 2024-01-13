<template>
    <div class="row justify-content-center align-items-center h-100 p-3">
        <Head title="Forgot Password" />
        <div class="col-md-6">
            <div class="card">
                <div class="card-body h-100 text-center">
                    <h5>Reset Your Password</h5>
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
                            <ResetPasswordForm :email="email" :token="token" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body text-center">
                    <h5>Password Rules</h5>
                    <ul class="list-group">
                        <li v-for="rule in rules" class="list-group-item">
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

const app = useAppStore();

defineProps<{
    email: string;
    token: string;
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
