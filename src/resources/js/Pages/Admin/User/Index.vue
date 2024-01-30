<template>
    <div>
        <Head title="User Administration" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            User Administration
                            <Link :href="$route('admin.user.create')">
                                <AddButton class="float-end" pill small>
                                    New User
                                </AddButton>
                            </Link>
                        </div>
                        <!-- <ListUsersTable :user-list="userList" /> -->
                        <!-- {{ userList }} -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    userList: user[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
