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
                    <th>
                        <span
                            v-if="markedNotifications.length"
                            class="float-end"
                        >
                            <CheckmarkBadge
                                tooltip="Mark Notifications as Read"
                                @click="handleNotifications('read')"
                            />
                            <DeleteBadge
                                tooltip="Delete Notifications"
                                @click="handleNotifications('delete')"
                            />
                        </span>
                        Subject:
                    </th>
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
                        <span class="float-end date-text">
                            {{ notification.created_at }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import CheckmarkBadge from "../_Base/Badges/CheckmarkBadge.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import { useAppStore } from "@/Store/AppStore";
import { computed } from "vue";
import {
    markedNotifications,
    allChecked,
    handleNotifications,
} from "@/State/NotificationState";

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

.date-text {
    color: #999999;
}
</style>
