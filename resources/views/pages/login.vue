<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 07 Feb 2022 16:06:27 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->

<template  >
    <Head :title="transactions.login" />
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0
 bg-gradient-to-r from-gray-100 to-gray-200

">
        <div class="h-full " >
                <div class=" flex flex-col justify-center py-12 sm:px-6 lg:px-8  ">
                    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md     ">
                        <div class="mx-auto mb-5 ">
                            <font-awesome-icon :icon="['fad', 'dice-d10']" class="ml-4" size="2x" /> <span class="ml-4 text-2xl  md:text-3xl font-light tracking-tighter">{{tenantCode}}@aiku</span>
                        </div>
                        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 sm:w-96     ">

                            <form class="space-y-6" @submit.prevent="submit">
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700">
                                        {{ transactions.username }}
                                    </label>
                                    <div class="mt-1">
                                        <input id="username" name="username" type="username" autocomplete="username" required=""
                                               v-model="form.username"
                                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">
                                        {{ transactions.password }}
                                    </label>
                                    <div class="mt-1">
                                        <input id="password" name="password" type="password" autocomplete="current-password" required=""
                                               v-model="form.password"
                                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input id="remember-me" name="remember-me" type="checkbox"
                                               v-model="form.remember"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                                            {{ transactions.remember_me }}
                                        </label>
                                    </div>


                                </div>

                                <div>
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                    >
                                        {{ transactions.login }}
                                    </button>
                                </div>
                            </form>


                            <div v-if="hasErrors" class="mt-4 text-center" >
                                <div class="font-medium text-red-600">{{ transactions.generic_error }}</div>

                                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                    <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                                </ul>
                            </div>

                            <div v-if="status" class="mb-4 font-medium text-sm text-center text-green-600">
                                {{ status }}
                            </div>

                        </div>

                        <div class="pt-4 text-center	 sm:w-96">
                            aiku.io
                        </div>

                    </div>
                </div>
            </div>

    </div>
</template>


<script>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {library} from '@fortawesome/fontawesome-svg-core';
import { faDiceD10 } from '@/private/pro-duotone-svg-icons'
library.add(faDiceD10);

import {Head, Link, usePage} from '@inertiajs/inertia-vue3';

export default {
    layout    : null,
    components: {
        Head,
        Link,FontAwesomeIcon
    },

    props: {
        status: String,
    },
    setup() {
        const transactions=usePage().props.value.labels;
        const tenantCode = usePage().props.value.tenantCode;
        return {tenantCode,transactions}
    },
    data() {
        return {
            form: this.$inertia.form({
                                         username: '',
                                         password: '',
                                         remember: false
                                     })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('login'), {
                onFinish: () => this.form.reset('password'),
            })
        }
    },

    computed: {
        errors() {
            return this.$page.props.errors
        },

        hasErrors() {
            return Object.keys(this.errors).length >0
        },
    }



}
</script>
