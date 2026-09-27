<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import { useEcho } from "@laravel/echo-vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { logout } from "@/wayfinder/routes";

const props = defineProps<{}>();

const messages = ref<string[]>([]);
const isFinished = ref<boolean>(false);

useEcho(
    "administration-channel",
    ".AdministrationEvent",
    (e: AdministrativeMessage) => {
        console.log(e);

        messages.value.push(e.msg);

        if (e.msg === "Backup Restored Successfully") {
            isFinished.value = true;
        }
    },
);

const goHome = () => {
    router.post(logout.url());
};
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
<template>
    <div class="h-screen flex flex-col justify-center items-center gap-3">
        <Card size="md" class="h-100 overflow-auto bg-black! text-white">
            <template v-for="msg in messages">
                <pre>{{ msg }}</pre>
            </template>
        </Card>
        <Card v-if="isFinished" size="sm">
            <BaseButton text="Login Again" class="w-full" @click="goHome" />
        </Card>
    </div>
</template>
