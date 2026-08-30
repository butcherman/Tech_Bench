<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import UserAvatar from "./UserAvatar.vue";
import { computed } from "vue";

const props = defineProps<{
    user: User;
}>();

const isActive = computed(() => (props.user.deleted_at ? false : true));

const activeText = computed(() => (isActive.value ? "Active" : "Disabled"));
const activeColor = computed(() =>
    isActive.value ? "text-success" : "text-danger",
);
</script>

<template>
    <Card class="flex flex-col gap-2">
        <div class="flex gap-3">
            <UserAvatar :user="user" />
            <div>
                <div>{{ user.full_name }}</div>
                <div>{{ user.role_name }}</div>
                <div>
                    <fa-icon icon="circle" :class="activeColor" />
                    {{ activeText }}
                </div>
            </div>
        </div>
        <div class="border-t border-t-slate-300 mt-4 pt-5">
            <table>
                <tbody>
                    <tr>
                        <td class="pe-15 text-muted">Username</td>
                        <td>{{ user.username }}</td>
                    </tr>
                    <tr>
                        <td class="pe-15 text-muted">Email</td>
                        <td>{{ user.email }}</td>
                    </tr>
                    <tr>
                        <td class="pe-15 text-muted">Role</td>
                        <td>{{ user.role_name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Card>
</template>
