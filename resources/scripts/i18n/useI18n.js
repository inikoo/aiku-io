/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Mar 2022 04:50:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

import {DateTime} from 'luxon';
import {useLocaleStore} from '@/scripts/stores/locale';

export default function() {
    const locale = useLocaleStore();

    const __number = (value) => {
        if (Number.isFinite(value)) {
            return new Intl.NumberFormat(locale['language']).format(value);
        }
        return value;

    };

    const __date = (value) => {
        return DateTime.fromISO(value, {locale: locale['language']}).
            toLocaleString(DateTime.DATE_FULL);
    };

    return {
        __number, __date,
    };
}
