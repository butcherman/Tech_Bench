<template>
    <div class="row justify-content-center">
        <Head title="Customer Not Found" />
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Error: 404</div>
                    <div class="row">
                        <div class="col-4">
                            <img
                                src="/images/error/searching.png"
                                alt="Cannot find it..."
                                class="img-fluid"
                            />
                        </div>
                        <div class="col">
                            <h5 class="text-center">
                                Well this is embarrassing...
                            </h5>
                            <p class="text-center">
                                It seems I cannot find the customer you are
                                looking for.
                            </p>
                            <p class="text-center">
                                You should probably head back to the search page
                                and try again.
                            </p>
                            <p class="text-center">
                                <Link
                                    as="button"
                                    :href="$route('customers.index')"
                                    class="btn btn-info"
                                    >Back to Search Page</Link
                                >
                            </p>
                        </div>
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
