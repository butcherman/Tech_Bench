<template>
    <div>
        <div class="row">
            <div class="col-12">
                <h4 class="text-center">Expired Link</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center">
                            The link you are trying to access has expired.
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import KbLayout from "@/Layouts/KbLayout.vue";
</script>

<script lang="ts">
export default { layout: KbLayout };
</script>
