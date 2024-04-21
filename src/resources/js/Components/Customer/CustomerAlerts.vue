<template>
    <div class="alertWrapper">
        <div
            v-for="alert in customerAlerts"
            :key="alert.alert_id"
            class="alert text-center"
            :class="`alert-${alert.type}`"
        >
            <fa-icon :icon="getAlertIcon(alert.type)" class="float-start" />
            {{ alert.message }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { customerAlerts } from "@/State/CustomerState";
import { getAlertIcon } from "@/Modules/AlertStyling.module";
</script>
