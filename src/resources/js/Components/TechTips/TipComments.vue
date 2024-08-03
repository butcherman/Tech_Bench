<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Discussion:</div>
                    <h5 v-if="!tipComments.length" class="text-center">
                        No Comments Yet
                    </h5>
                    <div
                        v-for="comment in tipComments"
                        :key="comment.comment_id"
                        class="border rounded m-2 p-2"
                    >
                        <div class="mb-2">
                            <div>
                                <DeleteBadge
                                    v-if="canDelete(comment)"
                                    class="float-end"
                                    @click="deleteComment(comment)"
                                />
                                <EditBadge
                                    v-if="canEdit(comment)"
                                    class="float-end"
                                    @click="editComment(comment)"
                                />
                                <span
                                    v-if="
                                        !canEdit(comment) && !canDelete(comment)
                                    "
                                    class="float-end text-muted pointer"
                                    title="Flag this comment as inappropriate"
                                    v-tooltip
                                    @click="flagComment(comment)"
                                >
                                    <fa-icon icon="flag" />
                                </span>
                            </div>
                            <span v-if="comment.is_flagged" class="text-danger">
                                <fa-icon icon="triangle-exclamation" />
                                This comment has been flagged for review
                                <fa-icon icon="triangle-exclamation" />
                            </span>
                            <span v-else>
                                {{ comment.comment }}
                            </span>
                        </div>
                        <div class="border-top text-secondary">
                            {{ comment.author }}
                            <span class="float-end">
                                {{ comment.created_at }}
                            </span>
                        </div>
                    </div>
                    <TechTipCommentForm
                        v-if="permissions.comment"
                        :tip-slug="tipSlug"
                        class="mt-4"
                    />
                </div>
            </div>
        </div>
        <Modal
            ref="editCommentModal"
            title="Edit comment"
            @hidden="activeComment = null"
        >
            <TechTipCommentForm
                v-if="activeComment"
                :tip-slug="tipSlug"
                :comment-data="activeComment"
                @success="editCommentModal?.hide()"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import TechTipCommentForm from "@/Forms/TechTips/TechTipCommentForm.vue";
import EditBadge from "../_Base/Badges/EditBadge.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import Modal from "../_Base/Modal.vue";
import { ref } from "vue";
import { useAppStore } from "@/Store/AppStore";
import { router } from "@inertiajs/vue3";
import verifyModal from "@/Modules/verifyModal";

const props = defineProps<{
    tipComments: tipComment[];
    tipSlug: string;
    permissions: techTipPermissions;
}>();

const appData = useAppStore();
const editCommentModal = ref<InstanceType<typeof Modal> | null>(null);
const activeComment = ref<tipComment | null>(null);

const canEdit = (commentData: tipComment) => {
    return commentData.user_id === appData.user?.user_id;
};

const canDelete = (commentData: tipComment) => {
    return (
        commentData.user_id === appData.user?.user_id ||
        props.permissions.manage
    );
};

const flagComment = (commentData: tipComment) => {
    router.post(
        route("tech-tips.comments.flag", [
            props.tipSlug,
            commentData.comment_id,
        ]),
        {
            preserveScroll: true,
        }
    );
};

const editComment = (commentData: tipComment) => {
    activeComment.value = commentData;
    editCommentModal.value?.show();
};

const deleteComment = (commentData: tipComment) => {
    verifyModal("Do you want to delete this comment?").then((res) => {
        if (res) {
            router.delete(
                route("tech-tips.comments.destroy", commentData.comment_id),
                {
                    preserveScroll: true,
                }
            );
        }
    });
};
</script>
