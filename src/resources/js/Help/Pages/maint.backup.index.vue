<template>
    <div class="text-center">
        <h5>Backups</h5>
        <p>
            Backup files can be managed here. To manually run a backup, click on
            <strong>Run Local Backup</strong>.
        </p>
        <p>
            All existing backup files will be listed here. Files can be
            downloaded or deleted as necessary.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
