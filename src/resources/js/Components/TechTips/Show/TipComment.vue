<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import TipCommentForm from "@/Forms/TechTip/TipCommentForm.vue";
import { computed, useTemplateRef } from "vue";
import { useAuthStore } from "@/Stores/AuthStore";

const props = defineProps<{
    comment: techTipComment;
    permissions: techTipPermissions;
    techTip: techTip;
}>();

const auth = useAuthStore();
const modal = useTemplateRef("edit-comment-modal");

/**
 * Determine if user can delete.  Admin or creator can delete.
 */
const canDelete = computed(() => canEdit.value || props.permissions.manage);

/**
 * Determine if user can edit.  Only the creator of the comment can edit.
 */
const canEdit = computed(() => props.comment.user_id === auth.user.user_id);
</script>

<template>
    <div>
        <Card class="my-2">
            <template #title>
                <div class="flex flex-row-reverse gap-1">
                    <DeleteBadge
                        v-if="canDelete"
                        confirm-msg="Do you want to delete this comment?"
                        confirm
                        delete-method
                        preserve-scroll
                        :href="
                            $route('tech-tips.comments.destroy', [
                                techTip.slug,
                                comment.comment_id,
                            ])
                        "
                        v-tooltip="'Delete Comment'"
                    />
                    <EditBadge
                        v-if="canEdit"
                        v-tooltip="'Edit Comment'"
                        @click="modal?.show()"
                    />
                    <Link
                        v-if="!canDelete"
                        class="pointer text-warning"
                        :href="
                            $route('tech-tips.comments.flag', [
                                techTip.slug,
                                comment.comment_id,
                            ])
                        "
                        v-tooltip="'Flag as inappropriate'"
                    >
                        <fa-icon icon="flag" />
                    </Link>
                </div>
            </template>
            <div v-if="comment.is_flagged" class="text-danger text-center">
                <fa-icon icon="triangle-exclamation" />
                This comment has been flagged for review
                <fa-icon icon="triangle-exclamation" />
            </div>
            <div v-else>
                {{ comment.comment }}
            </div>
            <template #footer>
                <div class="flex justify-between text-muted">
                    <div>{{ comment.author }}</div>
                    <div>{{ comment.created_at }}</div>
                </div>
            </template>
        </Card>
        <Modal ref="edit-comment-modal" title="Edit Comment">
            <TipCommentForm :comment="comment" :tech-tip="techTip" />
        </Modal>
    </div>
</template>
