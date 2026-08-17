<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { email, authenticator } from "@/wayfinder/routes/two-factor/setup";

const props = defineProps<{
    required: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card size="lg" title="Setup Two Factor Authentication">
            <h4 v-if="required" class="text-center">
                You must setup Two Factor Authentication to continue.
            </h4>
            <p class="text-center">
                Please select to get the two factor code via email, or setup an
                authenticator app.
            </p>
            <div class="my-4 flex flex-col gap-2 items-center">
                <BaseButton
                    :href="email.url()"
                    text="Email Verification Code"
                    class="w-3/4 md:w-1/2"
                />
                <BaseButton
                    :href="authenticator.url()"
                    text="Setup Authenticator App"
                    class="w-3/4 md:w-1/2"
                />
            </div>
        </Card>
    </div>
</template>
