<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import AddBadge from "@/core/components/badges/AddBadge.vue";
import BooleanBadge from "@/core/components/badges/BooleanBadge.vue";
import DeleteBadge from "@/core/components/badges/DeleteBadge.vue";
import EditBadge from "@/core/components/badges/EditBadge.vue";
import CopyBadge from "@/core/components/badges/CopyBadge.vue";

defineOptions({ layout: AppLayout });

const props = defineProps<{}>();
</script>

<template>
    <div class="flex flex-col gap-2">
        <div class="flex justify-center gap-2">
            <Card title="Badge Components">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-wrap">
                        <BaseBadge text="Base" />
                        <div>
                            <span class="hidden md:inline">&nbsp;- </span>
                            Base Badge
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <AddBadge />
                        <div>
                            <span class="hidden md:inline">&nbsp;- </span>
                            Add Badge
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <BooleanBadge :value="true" />
                        <div>
                            <span class="hidden md:inline"> - </span>
                            Boolean Badge True
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <BooleanBadge :value="false" />
                        <div>
                            <span class="hidden md:inline">&nbsp;- </span>
                            Boolean Badge False
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <DeleteBadge />
                        <div>
                            <span class="hidden md:inline">&nbsp;- </span>
                            Delete Badge
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <EditBadge />
                        <div>
                            <span class="hidden md:inline">&nbsp;- </span>
                            Edit Badge
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <CopyBadge />
                        <div>
                            <span class="hidden md:inline">&nbsp;- </span>
                            Copy Badge
                        </div>
                    </div>
                </div>
            </Card>
            <Card title="Badge Variants">
                <div class="flex flex-wrap gap-2">
                    <BaseBadge class="flex-1" text="Danger" variant="danger" />
                    <BaseBadge class="flex-1" text="Dark" variant="dark" />
                    <BaseBadge class="flex-1" text="Error" variant="error" />
                    <BaseBadge class="flex-1" text="Help" variant="help" />
                    <BaseBadge class="flex-1" text="Info" variant="info" />
                    <BaseBadge class="flex-1" text="Light" variant="light" />
                    <BaseBadge
                        class="flex-1"
                        text="Primary"
                        variant="primary"
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Secondary"
                        variant="secondary"
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Success"
                        variant="success"
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Warning"
                        variant="warning"
                    />
                    <BaseBadge class="flex-1" text="None" />
                </div>
                <h4>Light</h4>
                <div class="flex flex-wrap gap-2">
                    <BaseBadge
                        class="flex-1"
                        text="Danger"
                        variant="danger"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Dark"
                        variant="dark"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Error"
                        variant="error"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Help"
                        variant="help"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Info"
                        variant="info"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Light"
                        variant="light"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Primary"
                        variant="primary"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Secondary"
                        variant="secondary"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Success"
                        variant="success"
                        light
                    />
                    <BaseBadge
                        class="flex-1"
                        text="Warning"
                        variant="warning"
                        light
                    />
                    <BaseBadge class="flex-1" text="None" light />
                </div>
            </Card>
            <Card title="Badge Sizes">
                <div class="flex flex-col gap-1 items-center">
                    <div>
                        <BaseBadge variant="primary" text="Large" size="lg" />
                    </div>
                    <div>
                        <BaseBadge variant="primary" text="Medium" size="md" />
                    </div>
                    <div>
                        <BaseBadge variant="primary" text="Small" size="sm" />
                    </div>
                    <div>
                        <BaseBadge
                            variant="primary"
                            text="Large Circle"
                            size="lg"
                            circle
                        />
                    </div>
                    <div>
                        <BaseBadge
                            variant="primary"
                            text="Medium Circle"
                            size="md"
                            circle
                        />
                    </div>
                    <div>
                        <BaseBadge
                            variant="primary"
                            text="Small Circle"
                            size="sm"
                            circle
                        />
                    </div>
                </div>
            </Card>
        </div>
    </div>
</template>
