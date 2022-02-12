/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 12 Feb 2022 17:04:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

import { defineStore } from 'pinia'

export const useLayoutStore = defineStore('layout', {
    state: () => ({
        modules: [],
        currentModels: [],
    }),


})

