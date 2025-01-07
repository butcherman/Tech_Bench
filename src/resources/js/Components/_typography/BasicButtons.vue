<template>
    <Card title="Basic Buttons">
        <div class="grid grid-cols-2">
            <div>
                <BaseButton class="m-2" text="Large Button" size="large" />
            </div>
            <div>
                <BaseButton
                    class="mx-2 my-4"
                    text="Small Button"
                    size="small"
                />
            </div>
            <div>
                <BaseButton class="m-2" text="Normal Button" size="normal" />
            </div>
            <div>
                <BaseButton class="m-2" text="Pill Button" pill />
            </div>
            <div>
                <BaseButton class="m-2" text="Icon Button" icon="poo" />
            </div>
            <div>
                <BaseButton class="m-2" text="Danger Button" variant="danger" />
            </div>
            <div>
                <BaseButton class="m-2" text="Dark Button" variant="dark" />
            </div>
            <div>
                <BaseButton class="m-2" text="Error Button" variant="error" />
            </div>
            <div>
                <BaseButton class="m-2" text="Help Button" variant="help" />
            </div>
            <div>
                <BaseButton class="m-2" text="Info Button" variant="info" />
            </div>
            <div>
                <BaseButton
                    class="mx-2 my-4"
                    text="Light Button"
                    variant="light"
                />
            </div>
            <div>
                <BaseButton
                    class="m-2"
                    text="Secondary Button"
                    variant="secondary"
                />
            </div>
            <div>
                <BaseButton
                    class="mx-2 my-4"
                    text="Success Button"
                    variant="success"
                />
            </div>
            <div>
                <BaseButton
                    class="m-2"
                    text="Warning Button"
                    variant="warning"
                />
            </div>
        </div>
    </Card>
</template>

<script setup lang="ts">
import Card from "../_Base/Card.vue";
import BaseButton from "../_Base/Buttons/BaseButton.vue";
</script>
