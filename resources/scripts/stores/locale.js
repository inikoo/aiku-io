/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 12 Feb 2022 05:21:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

import { defineStore } from 'pinia'

export const useLocaleStore = defineStore('locale', {
    state: () => ({
        translations: [],
        language: '',
    }),

    getters: {

        __: (state) => {
            return (index) => state.translations[index]??index
        },

    }
})
