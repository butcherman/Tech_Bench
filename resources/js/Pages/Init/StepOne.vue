<template>
    <div>
        <h5 class="text-center">Secure Administrator Account</h5>
        <p class="text-center">
            The first step is to make sure that this Administrator account is
            properly secured.
        </p>
        <p class="text-center">
            Please update your password to a more secure password.
        </p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <UserPasswordForm @success="advance" />
                <h5 class="text-center mt-4">Password Rules:</h5>
                <ul>
                    <li v-for="rule in rules" class="mx-2">
                        {{ rule }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import UserPasswordForm from "@/Forms/User/UserPasswordForm.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    rules: string[];
}>();

const advance = () => {
    router.get(route("init.step-2"));
};
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
