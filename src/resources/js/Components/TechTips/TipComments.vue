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
                        :key="comment.id"
                        class="border rounded m-2 p-2"
                    >
                        <div class="mb-2">
                            {{ comment.comment }}
                        </div>
                        <div class="border-top text-secondary">
                            {{ comment.author }}
                            <span class="float-end">
                                {{ comment.created_at }}
                            </span>
                        </div>
                    </div>
                    <TechTipCommentForm :tip-slug="tipSlug" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import TechTipCommentForm from "@/Forms/TechTips/TechTipCommentForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    tipComments: tipComment[];
    tipSlug: string;
}>();
</script>
