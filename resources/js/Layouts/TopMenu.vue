<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Tue, 11 Jan 2022 15:54:55 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template>
    <div class="border-b border-gray-200 ">
        <span v-for="item in items" :key="item.name">


            <span v-if="item.type=='modelOptions'" class="ml-1 mr-4 w-20 inline-block  ">
                        <span class="flex flex-row" :class="[isUrl(item.module)  ? 'text-gray-700' : 'text-gray-400 group-hover:text-gray-500', '']">
                            <span class="flex-none ">[</span>
                              <span  class="grow text-center">

                              </span>

                              <span class="flex-none ">
                                  <span v-if="!item.currentModel">
                                      3 <font-awesome-icon :icon="['fal', 'bars']" class="mr-1" aria-hidden="true"/>
                                  </span>
                                  <font-awesome-icon :icon="['fal', 'angle-down']" class="mr-1" aria-hidden="true"/> ]</span>
                        </span>
                       </span>
            <span v-else :class="[ item.type=='modelIndex' ?'':'mr-4',    'ml-2 ']">
            <Link
                as="button"
                :href="item['href']"
                :class="[isUrl(item.module) ? 'text-gray-700' : 'text-gray-400 group-hover:text-gray-500', '']">

                <font-awesome-icon
                    v-if="item['icon']"
                    :icon="item['icon']"
                    :class="[isUrl(item.module)  ? 'text-gray-800' : 'text-gray-400 group-hover:text-gray-500', 'mr-1 ']"
                    fixed-width aria-hidden="true"/>
                       {{ item['name'] }}

            </Link>
            </span>

        </span>
    </div>
</template>

<script>

// Aux icons
import {faAngleDown, faBars} from '@/private/pro-light-svg-icons';

library.add(faAngleDown,faBars);

// Module icons
import {faTachometerAltFast, faClipboardUser, faUserCircle, faStoreAlt, faStore} from '@/private/pro-light-svg-icons';

library.add(faTachometerAltFast, faClipboardUser, faUserCircle, faStoreAlt, faStore);

import {library} from '@fortawesome/fontawesome-svg-core';

import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Link} from '@inertiajs/inertia-vue3';

export default {
    props     : ['items', 'currentRoute'],
    components: {
        FontAwesomeIcon, Link,
    },

    methods: {
        isUrl(...urls) {
            let currentUrl = this.$page.url.substr(1);
            if (urls[0] === '') {
                return currentUrl === '';
            }
            return urls.filter((url) => currentUrl.startsWith(url)).length;
        },
    },

};
</script>
