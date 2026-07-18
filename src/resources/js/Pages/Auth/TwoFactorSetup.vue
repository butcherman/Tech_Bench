<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div
        class="flex justify-center items-center h-screen"
        style="border: 1px solid red"
    >
        <Card class="tb-card" title="Setup Two Factor Authentication">
            <h4 class="text-center">
                You must setup Two Factor Authentication to continue.
            </h4>
            <p class="text-center">
                Please select to get the two factor code via email, or setup an
                authenticator app.
            </p>
            <div class="my-4 flex flex-col items-center">
                <BaseButton
                    :href="$route('two-factor.setup.email')"
                    text="Email Verification Code"
                    class="w-1/2 my-2"
                />
                <BaseButton
                    :href="$route('two-factor.setup.authenticator')"
                    text="Setup Authenticator App"
                    class="w-1/2 my-2"
                />
            </div>
        </Card>
    </div>
</template>
