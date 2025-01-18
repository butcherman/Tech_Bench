<template>
    <Card title="Secure Administrator Account">
        <p class="text-center">
            Lastly, lets make sure that our Administrator Account is secure.
        </p>
        <div v-if="!step1b">
            <p class="text-center">
                Please update the System Administrator Account Settings
            </p>
            <UserForm
                class="w-full md:w-3/4 justify-self-center mt-3"
                :roles="roles"
                :user="user"
                init
                @success="nextStep"
            />
        </div>
        <div v-else>
            <p class="text-center">Please enter a new Administrator Password</p>
            <UserPasswordForm
                class="w-full md:w-3/4 justify-self-center mt-3"
                init
                @success="router.get($route('init.step-5'))"
            />
        </div>
    </Card>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import UserPasswordForm from "@/Forms/User/UserPasswordForm.vue";
import UserForm from "@/Forms/Admin/User/UserForm.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps<{
    rules: string[];
    roles: userRole[];
    user: user;
    hasPass: boolean;
}>();

const step1b = ref(false);

const nextStep = () => {
    if (props.hasPass) {
        router.get(route("init.step-5"));
    } else {
        step1b.value = true;
    }
};
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
