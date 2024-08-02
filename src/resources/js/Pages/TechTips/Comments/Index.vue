<template>
    <div>
        <Head title="Flagged Comments" />
        <TipDetailsTitle v-if="tipData" :tip-data="tipData" />
        <div class="row justify-content-center my-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Flagged Comments</div>
                        <h5 v-if="!flaggedComments.length" class="text-center">
                            No Flagged Comments
                        </h5>
                        <ul class="list-group">
                            <li
                                v-for="comment in flaggedComments"
                                :key="comment.comment_id"
                                class="list-group-item py-4"
                            >
                                <div class="mb-2">
                                    <div>
                                        <Link
                                            :href="
                                                $route(
                                                    'tech-tips.comments.destroy',
                                                    comment.comment_id
                                                )
                                            "
                                            method="delete"
                                        >
                                            <DeleteBadge
                                                class="float-end"
                                                tooltip="Delete this comment"
                                            />
                                        </Link>
                                        <Link
                                            :href="
                                                $route(
                                                    'tech-tips.comments.restore',
                                                    comment.comment_id
                                                )
                                            "
                                            class="float-end badge bg-success rounded-pill pointer mx-1"
                                            title="Remove Comment Flag"
                                            v-tooltip
                                        >
                                            <fa-icon icon="rotate" />
                                        </Link>
                                        <div
                                            class="border-bottom text-secondary"
                                        >
                                            <span v-if="!tipData">
                                                For tip:
                                                <Link
                                                    :href="
                                                        $route(
                                                            'tech-tips.show',
                                                            comment.tech_tip
                                                                ?.slug
                                                        )
                                                    "
                                                >
                                                    {{
                                                        comment.tech_tip
                                                            ?.subject
                                                    }}
                                                </Link>
                                            </span>
                                        </div>
                                    </div>
                                    {{ comment.comment }}
                                </div>
                                <div class="border-top text-secondary">
                                    {{ comment.author }}
                                    <span class="float-end">
                                        {{ comment.created_at }}
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TipDetailsTitle from "@/Components/TechTips/TipDetailsTitle.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    tipData?: techTip;
    flaggedComments: tipComment[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
