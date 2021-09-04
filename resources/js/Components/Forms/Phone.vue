<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 04 Sep 2021 20:47:49 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->
<template>
    <input  v-model="modelValue" autocomplete="password" type="hidden" />
    <vue-tel-input v-model="tmpPhone" @on-input="handleChange"  inputOptions.placeholder="''" :defaultCountry="defaultCountry"></vue-tel-input>
</template>
<script>
import {VueTelInput} from 'vue-tel-input';
import 'vue-tel-input/dist/vue-tel-input.css';



export default {
    components: {
        VueTelInput,
    },

    props: [
        'modelValue','defaultCountry',
    ],
    emits     : ['update:modelValue'],
    setup(props) {

        let tmpPhone = props.modelValue;

        return {
            tmpPhone,
        };
    },

    methods: {
        handleChange(e, a) {
            this.$emit('update:modelValue', a.number);
        },
    },
};
</script>
