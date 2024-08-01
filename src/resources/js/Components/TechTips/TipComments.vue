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
                                    v-if="canEdit(comment)"
                                    class="float-end"
                                />
                                <EditBadge
                                    v-if="canEdit(comment)"
                                    class="float-end"
                                />
                                <span
                                    v-if="!canEdit(comment)"
                                    class="float-end text-muted pointer"
                                    title="Flag this comment as inappropriate"
                                    v-tooltip
                                    @click="flagComment(comment)"
                                >
                                    <fa-icon icon="flag" />
                                </span>
                            </div>
                            {{ comment.comment }}
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
    </div>
</template>

<script setup lang="ts">
import TechTipCommentForm from "@/Forms/TechTips/TechTipCommentForm.vue";
import EditBadge from "../_Base/Badges/EditBadge.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import { ref, reactive, onMounted } from "vue";
import { useAppStore } from "@/Store/AppStore";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    tipComments: tipComment[];
    tipSlug: string;
    permissions: techTipPermissions;
}>();

const appData = useAppStore();

const canEdit = (commentData: tipComment) => {
    // return (
    //     commentData.user_id === appData.user?.user_id ||
    //     props.permissions.manage
    // );

    return false;
};

const flagComment = (commentData: tipComment) => {
    router.get(
        route("tech-tips.comments.show", [
            props.tipSlug,
            commentData.comment_id,
        ]),
        {
            preserveScroll: true,
        }
    );
};
</script>
