<template>
    <Card title="Advanced Buttons">
        <div>
            <AddButton class="m-2" />
            <AddButton class="m-2" text="Add Pill" pill />
            <AddBadge class="m-2" v-tooltip="'Add Badge'" />
            <br />
            <EditButton class="m-2" />
            <EditButton class="m-2" text="Edit Pill" pill />
            <EditBadge class="m-2" v-tooltip="'Edit Badge'" />
            <br />
            <DeleteButton
                class="m-1"
                confirm-msg="This Cannot Be Undone"
                confirm
                @accepted="console.log('accepted')"
            />
            <DeleteButton class="m-1" text="Delete Pill" pill />
            <DeleteBadge
                class="m-1"
                v-tooltip="'Delete Badge'"
                confirm-msg="Example Confirm Message"
                confirm
                @accepted="console.log('badge accepted')"
            />
        </div>
        <div class="m-2">
            <BaseBadge text="Basic Badge" />
        </div>
        <div class="m-2">
            <BaseBadge
                icon="house"
                v-tooltip="'Base Badge'"
                :href="$route('about')"
            />
            Icon Badge
        </div>
        <div class="m-2">
            <ClipboardCopy value="Random Text for Clipboard" />
            Copy to Clipboard
        </div>
        <div class="m-2">
            <RefreshButton />
            Refresh Page
        </div>
        <div class="m-2">
            <h3 class="inline">
                <BookmarkItem :is-bookmark="false" toggle-route="#" />
                Bookmark Item
            </h3>
        </div>
    </Card>
</template>

<script setup lang="ts">
import Card from "../_Base/Card.vue";
import AddButton from "../_Base/Buttons/AddButton.vue";
import AddBadge from "../_Base/Badges/AddBadge.vue";
import EditButton from "../_Base/Buttons/EditButton.vue";
import EditBadge from "../_Base/Badges/EditBadge.vue";
import DeleteButton from "../_Base/Buttons/DeleteButton.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import BaseBadge from "../_Base/Badges/BaseBadge.vue";
import ClipboardCopy from "../_Base/ClipboardCopy.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import BookmarkItem from "../_Base/BookmarkItem.vue";
</script>
