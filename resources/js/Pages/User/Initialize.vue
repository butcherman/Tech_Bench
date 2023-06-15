<template>
    <Head title="Setup Account" />
    <div>
        <h5 class="text-center">Welcome {{ user.full_name }} to the {{ app.name }}</h5>
        <h6 class="text-center">Please create a Password to finish setting up your account</h6>
        <InitPasswordForm :token="link" :user="user" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Password Rules:</h5>
                        <ul>
                            <li v-for="rule in passwordRules" class="mx-2">
                                {{ rule }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import InitPasswordForm from '@/Forms/User/InitPasswordForm.vue';

defineProps<{
    link: string;
    user: user;
    passwordRules: string[];
    app: appProps;
}>();
</script>

<script lang="ts">
export default { layout: [GuestLayout, AuthLayout] };
</script>
