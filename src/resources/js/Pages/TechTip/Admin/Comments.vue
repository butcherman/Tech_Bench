<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

defineProps<{
    flaggedComments: techTipComment[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Flagged Comments">
            <ResourceList :list="flaggedComments">
                <template #list-item="{ item }">
                    <Card>
                        <template #title>
                            <div class="flex">
                                <div class="grow">
                                    <BaseBadge
                                        icon="link"
                                        v-tooltip="'Visit Tech Tip'"
                                        :href="
                                            $route(
                                                'tech-tips.show',
                                                item.tip_id
                                            )
                                        "
                                    />
                                    For Tech Tip ID {{ item.tip_id }}
                                </div>
                                <div>
                                    <BaseBadge
                                        icon="rotate"
                                        class="mx-1"
                                        :href="
                                            $route(
                                                'admin.tech-tips.flagged-comments.restore',
                                                item.comment_id
                                            )
                                        "
                                        v-tooltip.left="'Restore This Comment'"
                                    />
                                    <DeleteBadge
                                        class="mx-1"
                                        :href="
                                            $route(
                                                'tech-tips.comments.destroy',
                                                [item.tip_id, item.comment_id]
                                            )
                                        "
                                        delete-method
                                        v-tooltip="'Delete Comment'"
                                    />
                                </div>
                            </div>
                        </template>
                        {{ item.comment }}
                        <template #footer>
                            <div v-for="flag in item.flags" :key="flag.id">
                                Flagged on
                                {{ flag.created_at }}
                                by
                                {{ flag.flagged_by }}
                            </div>
                        </template>
                    </Card>
                </template>
            </ResourceList>
        </Card>
    </div>
</template>
