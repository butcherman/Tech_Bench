<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";

const title = "Tech Bench Backups";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            Backup files can be managed here. To manually run a backup, click on
            <BaseButton size="small" text="Run Backup" />.
        </p>
        <p>
            All existing backup files will be listed here. Files can be
            downloaded or deleted as necessary.
        </p>
        <p class="md:w-3/4 text-center">
            Backup files can also be uploaded here as well, but restoring the
            database must be done from the Command Line by using the command
            <code class="bg-gray-700 text-white px-2 py-1">
                php artisan app:backup-restore
            </code>
        </p>
    </div>
</template>
