/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 11 Feb 2022 18:29:59 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import { library } from "@fortawesome/fontawesome-svg-core";


import { faDiceD10 } from '@/private/pro-duotone-svg-icons';


library.add(faDiceD10);

// App icons
import { faSlidersHSquare, faHistory, faPlus, faEdit, faPortalExit, faRobot, faAngleRight, faAngleDown, faLayerGroup,faTachometerAltFast,faBars } from '@/private/pro-light-svg-icons';
library.add(faSlidersHSquare, faHistory, faPlus, faEdit, faPortalExit, faRobot, faAngleRight, faAngleDown, faLayerGroup,faTachometerAltFast,faBars);

import { faBirthdayCake, faMars, faVenus } from '@/private/pro-regular-svg-icons';
library.add(faBirthdayCake, faMars, faVenus);





// Module icons
import {
    faClipboardUser, faDiceD4, faStoreAlt, faPersonCarry, faGlobe,
    faWarehouseAlt, faAppleCrate, faAbacus, faIndustry, faInventory, faPalletAlt,
    faUserSecret,faHandHoldingBox,faUser,faShoppingCart
} from '@/private/pro-light-svg-icons';

library.add( faClipboardUser, faDiceD4, faStoreAlt, faPersonCarry, faGlobe,
    faWarehouseAlt, faAppleCrate, faAbacus, faIndustry, faInventory, faPalletAlt,
    faUserSecret,faHandHoldingBox,faUser,faShoppingCart);



// For inventory
import { faBox } from '@/private/pro-light-svg-icons';
library.add(faBox);

// For account section

import { faCheckCircle, faTimesCircle, } from '@fortawesome/free-solid-svg-icons';
library.add(faCheckCircle, faTimesCircle);
import {  faUserCircle,faUserAlien } from '@/private/pro-light-svg-icons';
library.add(faUserAlien, faUserCircle);

// For employee model
import { faTasks,faUserHardHat } from '@/private/pro-light-svg-icons';
library.add(faTasks,faUserHardHat);
export default FontAwesomeIcon;
