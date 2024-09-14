<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">User Settings</div>
                    <p class="text-center">
                        Now we will setup User Security Settings. Adjust the
                        password policy as you see fit.
                    </p>
                    <PasswordPolicyForm
                        :policy="policy"
                        init
                        @success="router.get($route('init.step-4'))"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import PasswordPolicyForm from "@/Forms/Admin/User/PasswordPolicyForm.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    policy: passwordPolicy;
}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
