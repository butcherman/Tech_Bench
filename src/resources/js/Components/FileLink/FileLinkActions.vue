<template>
    <div class="card">
        <div class="card-body justify-content-center">
            <a
                v-if="!link.is_expired && !isAdmin"
                :href="`mailto:?subject=A File Link Has Been Created For You
                                &body=View the link details here: ${link.public_href}`"
                class="btn btn-info rounded-5 m-1 w-100"
            >
                <fa-icon icon="envelope" />
                Email Link
            </a>
            <Link
                v-if="!isAdmin"
                :href="$route('links.edit', link.link_id)"
                class="w-100 my-1"
            >
                <EditButton class="w-100" text="Edit Link" pill />
            </Link>
            <Link
                v-if="!link.is_expired"
                :href="$route('links.extend', link.link_id)"
                class="btn btn-warning rounded-5 m-1 w-100"
            >
                <fa-icon icon="calendar-plus" />
                Extend Link 30 Days
            </Link>
            <button
                v-if="!link.is_expired"
                class="btn btn-warning rounded-5 m-1 w-100"
                @click="disableLink"
            >
                <fa-icon icon="link-slash" />
                Disable Link
            </button>
            <DeleteButton
                class="my-1 w-100"
                text="Delete Link"
                pill
                @click="deleteLink"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import EditButton from "../_Base/Buttons/EditButton.vue";
import DeleteButton from "../_Base/Buttons/DeleteButton.vue";
import { router } from "@inertiajs/vue3";
import verifyModal from "@/Modules/verifyModal";

const props = defineProps<{
    link: fileLink;
    isAdmin?: boolean;
}>();

/**
 * Set Expire Field to yesterday
 */
const disableLink = () => {
    verifyModal(
        "This link and its files will no longer be accessible publicly"
    ).then((res) => {
        if (res) {
            router.get(route("links.expire", props.link.link_id));
        }
    });
};

/**
 * Delete Link
 */
const deleteLink = () => {
    verifyModal("This link and its files will be destroyed").then((res) => {
        if (res) {
            if (props.isAdmin) {
                router.delete(
                    route("admin.links.manage.destroy", props.link.link_id)
                );
            } else {
                router.delete(route("links.destroy", props.link.link_id));
            }
        }
    });
};
</script>
