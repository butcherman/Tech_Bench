<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Email Settings</div>
                    <p class="text-center">
                        The Tech Bench requires email settings in order to send
                        notification emails. Please enter the proper email
                        server information below.
                    </p>
                    <EmailConfigForm
                        :settings="settings"
                        init
                        @success="allowNext = true"
                    />
                    <Link
                        v-if="allowNext"
                        as="button"
                        :href="$route('init.test-email')"
                        class="btn btn-warning w-100 my-2"
                        preserve-state
                    >
                        Send Test Email (uses saved values)
                    </Link>
                    <div v-if="allowNext" class="separator"></div>
                    <Link
                        v-if="allowNext"
                        as="button"
                        :href="$route('dashboard')"
                        class="btn btn-info w-100 my-2"
                    >
                        Continue
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import EmailConfigForm from "@/Forms/Admin/Config/EmailConfigForm.vue";
import { ref } from "vue";

defineProps<{
    settings: { [key: string]: string };
}>();

const allowNext = ref(false);
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
