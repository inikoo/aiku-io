<template>
  <div class="text-center">
    <div class="mx-auto w-full ">  
    <FontAwesomeIcon :icon="['fal','cactus']" size="lg" class="text-gray-300"></FontAwesomeIcon>
    <FontAwesomeIcon :icon="['fal','skull-cow']" size="2x" class="text-gray-500 mx-3"></FontAwesomeIcon>
    <FontAwesomeIcon :icon="['fal','cactus']" size="lg" class="text-gray-300"></FontAwesomeIcon>
   </div> 
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{data.title??'No records'}}</h3>
    <div v-if="data.newModel" >

    <p class="mt-1 text-sm text-gray-500">
      Get started by creating a new project.
    </p>
    <div class="mt-6">
      <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
        New Project
      </button>
    </div>
    </div>
  </div>
</template>

<script setup>
import { PlusIcon } from '@heroicons/vue/solid'
import {library} from '@fortawesome/fontawesome-svg-core';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {faCactus, faSkullCow} from '@/private/pro-light-svg-icons';
library.add(faCactus, faSkullCow);

const props = defineProps({
  data: Object,
});

</script>