<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";

const title = "Tech Bench Features";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            All Tech Bench additional features can be enabled and disabled here.
        </p>
        <p>
            For a brief description of each feature, click on the
            <BaseBadge icon="question" /> icon.
        </p>
    </div>
</template>
