<template>
    <App>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Create New Equipment</div>
                        <form class="vld-parent" @submit="onSubmit" novalidate>
                            <Loading :active="isSubmitting" :is-full-page="false">
                                <HalfCircleLoader />
                            </Loading>
                            <TextInput
                                id="category"
                                name="category"
                                label="Equipment Category"
                                disabled
                            />
                            <TextInput
                                id="name"
                                name="name"
                                label="Equipment Name"
                            />

                            <fieldset>
                                <legend>
                                    Customer Information to Gather
                                    <small>(drag to re-order)</small>
                                </legend>
                                <div
                                    v-if="useIsFormTouched().value && errors['custData']"
                                    class="text-center text-danger"
                                >
                                    {{ errors['custData'] }}
                                </div>
                                <draggable
                                    v-model="fields"
                                    item-key="index"
                                    @end="onDragEnd"
                                >
                                    <template #item="{element, index}">
                                        <div class="input-group">
                                            <span
                                                class="pointer input-group-text"
                                                title="Drag to re-order"
                                                v-tooltip
                                            >
                                                <fa-icon icon="fa-sort" />
                                            </span>
                                            <DatalistInput
                                                :id="`custData-${element.key}`"
                                                :name="`custData[${index}]`"
                                                label="Input information to gather for customers"
                                                :datalist="dataList"
                                            />
                                            <span
                                                class="pointer input-group-text text-danger"
                                                title="Remove this item"
                                                v-tooltip
                                                @click="remove(index)"
                                            >
                                                <fa-icon icon="fa-xmark" />
                                            </span>
                                        </div>
                                    </template>
                                </draggable>
                                <button
                                    type="button"
                                    class="btn btn-primary btn-pill float-end"
                                    @click="push('')"
                                >
                                    <fa-icon icon="fa-plus" />
                                    Add
                                </button>
                            </fieldset>
                            <SubmitButton
                                :submitted="isSubmitting"
                                text="Create Equipment"
                                class="mt-auto"
                            />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App                      from '@/Layouts/app.vue'
    import TextInput                from '@/Components/Base/Input/TextInput.vue';
    import DatalistInput            from '@/Components/Base/Input/DatalistInput.vue';
    import SubmitButton             from '@/Components/Base/Input/SubmitButton.vue';
    import Loading                  from 'vue3-loading-overlay';
    import HalfCircleLoader         from '@/Components/Base/Loader/HalfCircleLoader.vue';
    import draggable                from 'vuedraggable';
    import { ref }                  from 'vue';
    import { useForm }              from '@inertiajs/inertia-vue3';
    import { array_move }           from '@/Modules/helpers.module';
    import { useForm as useVeeForm,
             useIsFormTouched,
             useFieldArray }        from 'vee-validate';
    import * as yup                 from 'yup';
    import type { categoryList }    from '@/Types';

    const props = defineProps<{
        category: categoryList;
        dataList: string[];
    }>();

    const isSubmitting       = ref<boolean>(false);
    const { handleSubmit,
            values, errors } = useVeeForm({
        initialValues: {
            category: props.category.name,
            name    : '',
            custData: ['', ''],
        },
        validationSchema: {
            category: yup.string().required(),
            name    : yup.string().required('Please enter a name for the Equipment'),
            custData: yup.array().test('min-one', 'You must provide information to gather for customers', function(value) {
                if(value && value.length)
                {
                    let passed = false;
                    value.forEach((elem:string) => { if(elem.length) passed = true});

                    return passed;
                }

                return false;
            }).of(yup.string().test('no-duplicates', 'Duplicate entries are not allowed', function(value) {
                let valCount = 0;
                this.parent.forEach((elem:string) => {
                    if(elem.length > 0 && elem === value) valCount++;
                });

                return valCount <= 1;
            })),
        }
    });

    const { remove, push, fields } = useFieldArray('custData');

    const onSubmit = handleSubmit(form => {
        isSubmitting.value = true;
        const formData     = useForm(form);
        formData.post(route('equipment.store'), {
            onFinish: () => isSubmitting.value = false,
        });
    });

    type dragEvent = {
        oldIndex: number;
        newIndex: number;
    } & Event;

    /**
     * After a drag event is done, update the form with the proper order
     */
    const onDragEnd = (e:dragEvent) => {
        array_move(values.custData, e.oldIndex, e.newIndex);
    }
</script>

<style scoped lang="scss">
    @import 'vue3-loading-overlay/dist/vue3-loading-overlay.css';

    fieldset {
        border : 1px solid rgb(165, 163, 163, 0.5);
        margin : 5px;
        padding: 10px;
        legend {
            font-size: 1.2em;
            small {
                font-size: 0.7em;
            }
        }
    }

    .input-group-text {
        margin-bottom: 1rem;
    }
</style>
