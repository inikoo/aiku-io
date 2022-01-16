

    <template>
        <Head title="Welcome" />

        <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">


                <div class="mx-auto ">
                    <font-awesome-icon :icon="['fad', 'dice-d10']" class="ml-4" size="2x" /> <span class="ml-4 text-3xl font-light tracking-tighter">{{tenantCode}}@aiku</span>
                </div>



            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">




                    <form class="space-y-6" @submit.prevent="submit">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">
                                Username
                            </label>
                            <div class="mt-1">
                                <input id="username" name="username" type="username" autocomplete="username" required=""
                                v-model="form.username"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
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
                                    Remember me
                                </label>
                            </div>


                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            >
                                Log in
                            </button>
                        </div>
                    </form>


                 <div v-if="hasErrors" class="mt-4 text-center" >
                    <div class="font-medium text-red-600">Whoops! Something went wrong.</div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                    </ul>
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-center text-green-600">
                    {{ status }}
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
        const tenantCode = usePage().props.value.tenantCode;
        return {tenantCode}
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
