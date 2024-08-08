<template>
    <div>
        <UserNotifications />
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserNotifications from "@/Components/User/UserNotifications.vue";
import { useAppStore } from "@/Store/AppStore";
import { ref, reactive, onMounted } from "vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
