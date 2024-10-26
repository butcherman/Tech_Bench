<template>
    <div>
        <h5 class="text-center">User Login Activity Report</h5>
        <p class="text-center">
            This report will list all successful login attempts between the
            start and end dates for the selected users.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
