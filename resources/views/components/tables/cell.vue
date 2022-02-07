<template>
    <span v-bind:title="data.cellTitle">
    <span v-if="data.preSuffixHtml" v-html="data.preSuffixHtml"></span>
    <font-awesome-icon v-if="data.icon" :icon="data.icon"  :class="[data.iconClass,'flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400']"  aria-hidden="true"/>
    <span v-bind:title="data.labelTitle">{{data.label}}</span>
    <span v-if="data.postSuffixHtml" v-html="data.postSuffixHtml"></span>
    </span>
</template>

<script setup>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

const props = defineProps({
  data: Object,
});
</script>