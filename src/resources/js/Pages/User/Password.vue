<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <UserPasswordForm />
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
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
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserPasswordForm from "@/Forms/User/UserPasswordForm.vue";

defineProps<{
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
