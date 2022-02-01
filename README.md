### Database preparations

If using psql , a case-insensitive collation need to be created

CREATE COLLATION utf8mb4_unicode_ci (provider = icu, locale = 'und-u-ks-level2', deterministic = false);


Copyright (C) Raul A Perusquia Flores - All Rights Reserved GNU AGPLv3

