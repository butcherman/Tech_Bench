<template>
    <v-dialog v-model="isOpen" persistent>
        <div class="grid gap-2 md:place-items-center">
            <Card class="reasonable-width">
                <h5 class="text-center">
                    A verification code has been sent to your email address.
                </h5>
                <p class="text-center mb-4">Please input the code below.</p>
                <div>
                    <TwoFactorForm :allow-remember="allowRemember" />
                </div>
                <div class="mt-4 text-sm text-center">
                    <p>Need another code?</p>
                    <Link
                        as="button"
                        :href="$route('dashboard')"
                        class="btn bg-blue-200 hover:bg-blue-400 p-1"
                    >
                        Send New Verification Code
                    </Link>
                </div>
            </Card>
        </div>
    </v-dialog>
</template>

<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import TwoFactorForm from "@/Forms/Auth/TwoFactorForm.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import { ref } from "vue";

defineProps<{
    allowRemember: boolean;
}>();

const isOpen = ref(true);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
