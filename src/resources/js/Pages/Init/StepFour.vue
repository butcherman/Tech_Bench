<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Step4</div>
                    Step 4
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import UserPasswordForm from "@/Forms/User/UserPasswordForm.vue";
import { router } from "@inertiajs/vue3";

defineProps<{}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
