<template>
    <div>
        <div class="row">
            <div class="col-12">
                <p class="text-center">Please check your email, a verification code has been sent</p>
                <VerificationCodeForm />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import VerificationCodeForm from '@/Forms/Auth/VerificationCodeForm.vue';
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: [GuestLayout, AuthLayout] };
</script>
