<script setup lang="ts">
import { ref } from "vue";
import BaseModal from "./BaseModal.vue";

//

const isOpen = ref(true);
</script>

<template>
    <BaseModal :open="isOpen" position="top" hide-close hide-backdrop>
        Are you sure???
    </BaseModal>
</template>
