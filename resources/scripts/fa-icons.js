/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 11 Feb 2022 18:29:59 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

import {library} from "@fortawesome/fontawesome-svg-core";


import {faDiceD10} from '@/private/pro-duotone-svg-icons';


library.add(faDiceD10);

// App icons
import {faHome, faDotCircle, faSlidersHSquare, faHistory, faPlus, faEdit, faPortalExit, faRobot, faAngleRight, faAngleDown, faLayerGroup, faTachometerAltFast, faBars, faDiamond} from '@/private/pro-light-svg-icons';

library.add(faHome, faDotCircle, faSlidersHSquare, faHistory, faPlus, faEdit, faPortalExit, faRobot, faAngleRight, faAngleDown, faLayerGroup, faTachometerAltFast, faBars, faDiamond);

import {faBirthdayCake, faMars, faVenus} from '@/private/pro-regular-svg-icons';

library.add(faBirthdayCake, faMars, faVenus);


// Module icons
import {
    faDiceD4, faStoreAlt, faPersonCarry, faGlobe,
    faWarehouseAlt, faAppleCrate, faAbacus, faIndustry, faInventory, faPalletAlt,
    faUser, faShoppingCart
} from '@/private/pro-light-svg-icons';

library.add(faClipboardUser, faDiceD4, faStoreAlt, faPersonCarry, faGlobe,
    faWarehouseAlt, faAppleCrate, faAbacus, faIndustry, faInventory, faPalletAlt,
    faUser, faShoppingCart);

// Procurement
import {
     faHandHoldingBox,faUserSecret
} from '@/private/pro-light-svg-icons';

library.add(faHandHoldingBox,faUserSecret);

// Fulfilment
import {
    faPallet
} from '@/private/pro-light-svg-icons';

library.add(faPallet);


// For Marketing
import {faCashRegister} from '@/private/pro-light-svg-icons';

library.add(faCashRegister);

// For inventory
import {faBox} from '@/private/pro-light-svg-icons';

library.add(faBox);

// For account section

import {faCheckCircle, faTimesCircle,} from '@fortawesome/free-solid-svg-icons';
// @ts-ignore
library.add(faCheckCircle, faTimesCircle);
import {faUserCircle, faUserAlien} from '@/private/pro-light-svg-icons';

library.add(faUserAlien, faUserCircle);

// Human resources
import {faTasks, faUserHardHat,faClipboardUser} from '@/private/pro-light-svg-icons';

library.add(faTasks, faUserHardHat,faClipboardUser);
export default FontAwesomeIcon;
