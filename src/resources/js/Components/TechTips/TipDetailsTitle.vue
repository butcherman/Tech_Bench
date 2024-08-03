<template>
    <div>
        <h3>{{ tipData.subject }}</h3>
        <div class="tip-details">
            <span class="d-block d-sm-inline-block">
                <strong>ID: </strong>
                {{ tipData.tip_id }}
            </span>
            <span class="d-block d-sm-inline-block">
                <strong>Created: </strong>
                {{ tipData.created_at }}
            </span>
            <span v-if="tipData.updated_id" class="d-block d-sm-inline-block">
                <strong>Last Updated: </strong>
                {{ tipData.updated_at }}
            </span>
            <span v-if="tipData.public" class="d-block d-sm-inline-block">
                <fa-icon icon="star" class="text-success" />
                <strong>Public Tech Tip </strong>
            </span>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    tipData: techTip;
}>();
</script>

<style scoped lang="scss">
.tip-details {
    span {
        padding: 0 5px;
        border-right: 1px solid #999999;
        color: #999999;
        display: inline-block;
        &:last-child {
            border-right: none;
        }
    }
}
</style>
