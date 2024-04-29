<template>
    <div>
        <Head title="Reports" />
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">User Reports</div>
                        <ul class="list-group">
                            <Link
                                as="li"
                                :href="$route('reports.user.activity')"
                                class="list-group-item pointer"
                            >
                                User Login Activity Report
                            </Link>
                            <Link
                                as="li"
                                href="#"
                                class="list-group-item pointer"
                            >
                                User Contributions Report
                            </Link>
                            <Link
                                as="li"
                                href="#"
                                class="list-group-item pointer"
                            >
                                User Permissions Report
                            </Link>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
