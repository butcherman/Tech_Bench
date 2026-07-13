<script setup lang="ts">
const title = "Create New Equipment Data type";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p>Use this form to create a new Data Type Field for Equipment.</p>
            <p>
                Detailed descriptions of each field are listed below the form.
            </p>
        </div>
    </div>
</template>
