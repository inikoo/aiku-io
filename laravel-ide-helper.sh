php artisan ide-helper:generate
mv _ide_helper.php .phpstorm.meta.php/_ide_helper.php
#php artisan tenants:artisan "ide-helper:models  --write-mixin --dir='app/Models/CRM' --dir='app/Models/Delivery' --dir='app/Models/Financials' --dir='app/Models/Health' --dir='app/Models/Health' --dir='app/Models/Helpers' --dir='app/Models/HumanResources' --dir='app/Models/Inventory' --dir='app/Models/Marketing' --dir='app/Models/Marketplace' --dir='app/Models/Media' --dir='app/Models/Procurement' --dir='app/Models/Production' --dir='app/Models/Sales' --dir='app/Models/Utils' --dir='app/Models/Web' " --tenant=1
php artisan tenants:artisan "ide-helper:models  --write-mixin   " --tenant=1
mv _ide_helper_models.php .phpstorm.meta.php/_ide_helper_tenant_models.php
#php artisan ide-helper:models --write-mixin --dir="app/Models/Auth" --dir="app/Models/Account" --dir="app/Models/Admin"  --dir="app/Models/Assets"   -F '.phpstorm.meta.php/_ide_helper_landlord_models.php'
php artisan ide-helper:meta -F ".phpstorm.meta.php/_ide_phpstorm.meta.php"


