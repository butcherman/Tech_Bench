<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">StepFinish</div>
                    <h5 class="text-center">Setup Complete!</h5>
                    <p class="text-center">
                        You have completed the Wizard and are ready to get
                        going. Use any one of the links below to start using the
                        Tech Bench.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <a
                                :href="$route('dashboard')"
                                class="btn btn-info w-100"
                            >
                                Go To Dashboard
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a
                                :href="$route('admin.index')"
                                class="btn btn-info w-100"
                            >
                                Go To Administration
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";

defineProps<{}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
