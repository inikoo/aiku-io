<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 21 Aug 2021 21:59:21 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2021, Inikoo
  -  Version 4.0
  -->

<template>


    <simple-header :breadcrumbs="breadcrumbs" :module="'patients'" :actions="actions" :actionIcons="actionIcons">
        {{ title }}
        <template v-slot:action_options>
            <div class="ml-3 relative">
                <jet-dropdown align="right" width="48">

                    <template #content>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Type of patient') }}
                        </div>

                        <jet-dropdown-link :href="route('profile.show')">
                            {{ __('Adult') }}
                        </jet-dropdown-link>
                        <jet-dropdown-link :href="route('profile.show')">
                            {{ __('Minor') }}
                        </jet-dropdown-link>

                    </template>
                </jet-dropdown>
            </div>
        </template>

    </simple-header>

   <Form :formBlueprint="formBlueprint"  :postURL="'/patients/create'" />


</template>

<script>
import SimpleHeader from '@/Layouts/PageHeadings/SimpleHeader';
import JetDropdown from '@/Jetstream/Dropdown';
import JetDropdownLink from '@/Jetstream/DropdownLink';
import JetNavLink from '@/Jetstream/NavLink';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink';
import {__} from 'matice';
import Form from '@/Components/Forms/Form';

export default {
    components: {

        SimpleHeader, JetDropdown, JetDropdownLink, JetNavLink, JetResponsiveNavLink,Form
    },

    props  : ['title', 'buttons', 'breadcrumbs', 'actions', 'actionIcons', 'formBlueprint'],
    methods: {
        __: __,
    },

};
</script>


