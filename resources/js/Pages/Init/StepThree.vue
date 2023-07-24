<template>
    <div>
        <h5 class="text-center">Email Settings</h5>
        <p class="text-center">
            The Tech Bench requires email settings in order to send notification
            emails. Please enter the proper email server information below.
        </p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <EmailConfigForm
                    :settings="settings"
                    @success="allowNext = true"
                />
            </div>
        </div>
        <Link
            v-if="allowNext"
            :href="$route('init.step-4')"
            class="btn btn-info w-100"
            >Continue</Link
        >
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import EmailConfigForm from "@/Forms/Admin/Config/EmailConfigForm.vue";
import { ref } from "vue";

defineProps<{
    settings: emailSettings;
}>();

const allowNext = ref<boolean>(false);
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
