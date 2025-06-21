<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import PasswordPolicyForm from "@/Forms/Admin/User/PasswordPolicyForm.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    policy: passwordPolicy;
}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

<template>
    <Card title="User Settings" class="tb-card">
        <p class="text-center">
            Now we will setup User Security Settings. Adjust the password policy
            as you see fit.
        </p>
        <div>
            <PasswordPolicyForm
                class="w-full md:w-3/4 justify-self-center mt-3"
                :policy="policy"
                init
                @success="router.get($route('init.step-4'))"
            />
        </div>
    </Card>
</template>
