<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import BaseModal from "@/core/components/modals/BaseModal.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { ref } from "vue";

const modalOpen = ref(false);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex gap-2">
        <h2 class="pb-2">Dashboard</h2>
        <BaseButton text="open modal" @click="modalOpen = !modalOpen" />
        {{ modalOpen }}
        <BaseModal v-model:open="modalOpen" title="Modal Title">
            Modal stuff...
        </BaseModal>
    </div>
</template>
