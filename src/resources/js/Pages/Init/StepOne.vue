<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Secure Administrator Account</div>
                    <p class="text-center">
                        To start, lets make sure that our Administrator Account
                        is secure.
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
                            @success="step1b = true"
                        />
                    </div>
                    <div v-else>
                        <p class="text-center">
                            Please enter a new Administrator Password
                        </p>
                        <UserPasswordForm
                            @success="router.get($route('init.step-2'))"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Password Rules</div>
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
import InitLayout from "@/Layouts/InitLayout.vue";
import UserForm from "@/Forms/Admin/User/UserForm.vue";
import UserPasswordForm from "@/Forms/User/UserPasswordForm.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    rules: string[];
    roles: userRoles[];
    user: user;
}>();

const step1b = ref(false);
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
