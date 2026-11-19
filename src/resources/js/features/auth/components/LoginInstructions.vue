<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import LoginForm from "../forms/LoginForm.vue";
import { azureLogin } from "@/wayfinder/routes/index.js";

const props = defineProps<{
    allowOath: boolean;
}>();
</script>

<template>
    <div class="flex flex-col gap-3">
        <h3 class="text-center text-muted">Sign In</h3>
        <LoginForm :allow-oath="allowOath" />
        <template v-if="allowOath">
            <div class="text-center separator">&nbsp; or &nbsp;</div>
            <div class="text-center">
                <BaseButton
                    :href="azureLogin.url()"
                    class="w-full text-blue-800"
                >
                    Sign In With Microsoft
                </BaseButton>
            </div>
        </template>
    </div>
</template>

<style>
div.separator {
    display: flex;
    align-items: center;
    text-align: center;
    margin-top: 2em;
    margin-bottom: 2em;

    &::before,
    &::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #7b7a7a;
    }

    &:not(:empty)::before {
        margin-right: 0.25em;
    }

    &:not(:empty)::after {
        margin-left: 0.25em;
    }
}
</style>
