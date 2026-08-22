<script setup lang="ts">
import Card from "@/core/components/Card.vue";

const props = defineProps<{
    user: User;
    lastLogin: {
        ip_address: string;
        created_at: string;
    } | null;
    thirtyDayCount: number;
}>();
</script>

<template>
    <Card title="Account Activity">
        <table>
            <tbody>
                <tr>
                    <td class="pe-15 text-muted">Created</td>
                    <td>{{ user.created_at }}</td>
                </tr>
                <tr>
                    <td class="pe-15 text-muted">Profile Updated</td>
                    <td>{{ user.updated_at }}</td>
                </tr>
                <tr>
                    <td class="pe-15 text-muted">Last Login</td>
                    <td>{{ lastLogin?.created_at ?? "Never" }}</td>
                </tr>
                <tr>
                    <td class="pe-15 text-muted">Logins (30 days)</td>
                    <td>{{ thirtyDayCount }}</td>
                </tr>
            </tbody>
        </table>
    </Card>
</template>
