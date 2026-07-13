<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";

const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            All enabled users are listed here. You can search the users by their
            Name, Username, or Email Address.
        </p>
        <p>Click on a user to view more information about their profile.</p>
        <p>
            To immediately disable a user, select the
            <BaseBadge icon="user-slash" variant="danger" />
            button. This will immediately disable the user and log them out of
            any active sessions they may be in.
        </p>
    </div>
</template>
