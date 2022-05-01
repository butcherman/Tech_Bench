<!--
    Simple submit button for the form

    Props:
        button_text:    Text to display inside the button
        button_disable: Boolean value to note if button is disabled or not
        submitted:      Boolean - turn this prop on when form is submitted to stop double submissions
        button_variant: Standard Bootstrap variant name
-->

<template>
    <b-button type="submit" block :variant="button_variant" :disabled="button.disable">
        <span class="spinner-border spinner-border-sm text-danger" v-show="submitted"></span>
        {{button.text}}
    </b-button>
</template>

<script>
    export default {
        props: {
            button_text: {
                type:     String,
                default: 'Submit',
            },
            button_disable: {
                type:    Boolean,
                default: false,
            },
            submitted: {
                type:    Boolean,
                default: false,
            },
            button_variant: {
                type:     String,
                default: 'primary',
            }
        },
        data: function () {
            return {
                button: {
                    text:    this.button_text,
                    disable: this.button_disable,
                }
            }
        },
        watch: {
            submitted()
            {
                if(this.submitted)
                {
                    this.button.text    = "Loading...";
                    this.button.disable = true;
                }
                else
                {
                    this.button.text    = this.button_text;
                    this.button.disable = false;
                }
            },
            button_text()
            {
                this.button.text = this.button_text;
            },
            button_disable()
            {
                this.button.disable = this.button_disable;
            }
        }
    }
</script>
