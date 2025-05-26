<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Error: 404">
            <div class="flex flex-col">
                <div class="my-4">
                    <h5 class="text-center">Well this is embarrassing...</h5>
                    <p class="text-center">
                        It seems I cannot find the Tech Tip you are looking for.
                    </p>
                    <p class="text-center">
                        You should probably head back to the search page and try
                        again.
                    </p>
                    <p class="text-center">
                        <BaseButton
                            as="button"
                            :href="$route('tech-tips.index')"
                            class="btn btn-info"
                        >
                            Back to Search Page
                        </BaseButton>
                    </p>
                </div>
                <div class="flex justify-center">
                    <img
                        src="/images/error/searching.png"
                        alt="Cannot find it..."
                        class="w-1/3"
                    />
                </div>
            </div>
        </Card>
    </div>
</template>
