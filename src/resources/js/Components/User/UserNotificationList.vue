<template>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>
                        <input
                            type="checkbox"
                            class="form-check-input"
                            :value="true"
                            :indeterminate="indeterminate"
                            v-model="allChecked"
                            @change="toggleCheckAll"
                        />
                    </th>
                    <th>Subject:</th>
                    <th>Date:</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="!app.userNotifications.list.length">
                    <td colspan="3">
                        <h5 class="text-center">No Notifications</h5>
                    </td>
                </tr>
                <tr
                    v-for="(notification, index) in app.userNotifications.list"
                    :key="index"
                >
                    <td>
                        <input
                            type="checkbox"
                            class="form-check-input"
                            :value="notification.id"
                            v-model="markedNotifications"
                            @change="allChecked = false"
                        />
                    </td>
                    <td
                        class="pointer"
                        :class="{ bold: notification.read_at === null }"
                        @click="$emit('show-notification', notification)"
                    >
                        {{ notification.data.subject }}
                    </td>
                    <td
                        class="pointer"
                        :class="{ bold: notification.read_at === null }"
                        @click="$emit('show-notification', notification)"
                    >
                        {{ notification.created_at }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { markedNotifications, allChecked } from "@/State/NotificationState";
import { useAppStore } from "@/Store/AppStore";

defineEmits(["show-notification"]);
const app = useAppStore();
const indeterminate = computed<boolean>(
    () => markedNotifications.value.length > 0 && !allChecked.value
);

const toggleCheckAll = (): void => {
    if (allChecked.value) {
        markedNotifications.value = app.userNotifications.list.map((n) => n.id);
    } else {
        markedNotifications.value = [];
    }
};
</script>

<style scoped lang="scss">
.bold {
    font-weight: bold;
}
</style>
