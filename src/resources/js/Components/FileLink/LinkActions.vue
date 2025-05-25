<script setup lang="ts">
import BaseButton from "../_Base/Buttons/BaseButton.vue";
import DeleteButton from "../_Base/Buttons/DeleteButton.vue";
import EditButton from "../_Base/Buttons/EditButton.vue";
import { computed } from "vue";

const props = defineProps<{
    link: fileLink;
    isAdmin?: boolean;
}>();

const showOption = computed(() => !props.link.is_expired && !props.isAdmin);
</script>

<template>
    <div class="flex flex-col gap-2">
        <a
            v-if="showOption"
            :href="`mailto:?subject=A File Link Has Been Created For You
                                &body=View the link details here: ${link.public_href}`"
            class="w-full rounded-lg inline-block text-center bg-blue-400 px-3 py-2 text-white"
        >
            <fa-icon icon="envelope" />
            Email Link
        </a>
        <EditButton
            v-if="!isAdmin"
            class="w-full"
            text="Edit Link"
            :href="$route('links.edit', link.link_id)"
        />
        <BaseButton
            icon="calendar-plus"
            text="Extend Link 30 Days"
            :href="$route('links.extend', link.link_id)"
        />
        <BaseButton
            v-if="!link.is_expired"
            icon="link-slash"
            text="Disable Link"
            variant="error"
            :href="$route('links.expire', link.link_id)"
        />
        <DeleteButton
            text="Delete Link"
            confirm
            confirm-msg="Link and associated files will be deleted"
            :href="$route('links.destroy', link.link_id)"
        />
    </div>
</template>
