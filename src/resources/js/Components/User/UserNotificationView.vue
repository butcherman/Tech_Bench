<template>
    <div>
        <h3 class="text-center">{{ activeNotification?.data.subject }}</h3>
        <!-- <div v-html="activeNotification?.data" /> -->
        <h3 class="text-center">Notification Data</h3>
        Cras varius. Mauris turpis nunc, blandit et, volutpat molestie, porta
        ut, ligula. Sed mollis, eros et ultrices tempus, mauris ipsum aliquam
        libero, non adipiscing dolor urna a orci. Maecenas egestas arcu quis
        ligula mattis placerat. Quisque id mi. Phasellus dolor. Curabitur
        blandit mollis lacus. Fusce commodo aliquam arcu. Nullam quis ante.
        Fusce a quam. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet
        iaculis, ipsum. Phasellus ullamcorper ipsum rutrum nunc. Donec quam
        felis, ultricies nec, pellentesque eu, pretium quis, sem. Nunc interdum
        lacus sit amet orci. Curabitur ullamcorper ultricies nisi. Sed
        consequat, leo eget bibendum sodales, augue velit cursus nunc, quis
        gravida magna mi a libero.
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";
import { activeNotification } from "@/State/NotificationState";

// const props = defineProps<{}>();
</script>
