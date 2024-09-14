<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Secure Administrator Account</div>
                    <p class="text-center">
                        Lastly, lets make sure that our Administrator Account is
                        secure.
                    </p>
                    <div v-if="!step1b">
                        <p class="text-center">
                            Please update the System Administrator Account
                            Settings
                        </p>
                        <UserForm
                            :roles="roles"
                            :user="user"
                            init
                            @success="nextStep"
                        />
                    </div>
                    <div v-else>
                        <p class="text-center">
                            Please enter a new Administrator Password
                        </p>
                        <UserPasswordForm
                            init
                            @success="router.get($route('init.step-5'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import UserForm from "@/Forms/Admin/User/UserForm.vue";
import UserPasswordForm from "@/Forms/User/UserPasswordForm.vue";
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
