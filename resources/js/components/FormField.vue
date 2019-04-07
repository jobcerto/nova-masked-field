<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <input
            v-mask="format"
            :id="field.name"
            type="text"
            class="w-full form-control form-input form-input-bordered"
            :class="errorClasses"
            :placeholder="field.name"
            v-model="value"
            ></input>
        </template>
    </default-field>
</template>

<script>
    import {mask} from 'vue-the-mask'

    import { FormField, HandlesValidationErrors } from 'laravel-nova'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        directives: {
            mask
        },

        methods: {
        /*
         * Set the initial, internal value for the field.
         */
         setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
         fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
         handleChange(value) {
            this.value = value
        },
    },

    computed: {
        format() {
             const arrangedForamts = this.field.format.sort(function(a, b){
                return a.length - b.length;
          });
            const valueLength = this.value.replace(/[^\d\w]/gi, '').length;
            const mask = arrangedForamts.filter(format => format.replace(/[^#]/gi, '').length === valueLength)


            return mask[0] || arrangedForamts[0];
        }
    }
}
</script>
