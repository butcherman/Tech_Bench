<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Additional Information:</div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>REGEX Pattern:</strong>
                            To add validation to this field, you can enter a
                            Regular Expression sequence. For more information on
                            REGEX and testing help, check out an online REGEX
                            tester like
                            <a href="https://regex101.com" target="_blank"
                                >Regex 101</a
                            >.
                        </li>
                        <li class="list-group-item">
                            <strong>Field is Required</strong>
                            When enabled, an error message and icon will show
                            when there is no data in this field. Administrators
                            can run reports to check for customers that are
                            missing required fields.
                        </li>
                        <li class="list-group-item">
                            <strong>Field is Masked</strong>
                            When enabled, the field will not show its contents
                            unless directly interacted with (hover or click show
                            icon). This is helpful for password fields and other
                            sensitive information.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
