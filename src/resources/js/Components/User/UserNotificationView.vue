<template>
    <div>
        <button
            type="button"
            class="btn-close float-end"
            title="Close Notification"
            v-tooltip
            @click="hideNotification"
        />
        <h3 class="text-center">{{ activeNotification?.data.subject }}</h3>
        {{ activeNotification?.data }}
    </div>
</template>

<script setup lang="ts">
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import { ref, reactive, onMounted } from "vue";
import {
    activeNotification,
    hideNotification,
} from "@/State/NotificationState";

// const props = defineProps<{}>();
</script>
