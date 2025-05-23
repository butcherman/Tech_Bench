<script setup lang="ts">
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import Card from "@/Components/_Base/Card.vue";
import TipComment from "./TipComment.vue";
import TipCommentForm from "@/Forms/TechTip/TipCommentForm.vue";
import { Deferred } from "@inertiajs/vue3";

defineProps<{
    comments?: techTipComment[];
    permissions: techTipPermissions;
    techTip: techTip;
}>();
</script>

<template>
    <Card class="tb-card" title="Discussion">
        <Deferred data="comments">
            <template #fallback>
                <div class="flex justify-center">
                    <AtomLoader text="Loading Discussion.." />
                </div>
            </template>
            <h5 v-if="!comments?.length" class="text-center">No Comments</h5>
            <div v-for="comment in comments" :key="comment.comment_id">
                <TipComment
                    :comment="comment"
                    :permissions="permissions"
                    :tech-tip="techTip"
                />
            </div>
            <div v-if="permissions.comment">
                <TipCommentForm :tech-tip="techTip" />
            </div>
        </Deferred>
    </Card>
</template>
