<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Account{
/**
 * App\Models\Account\AccountAdmin
 *
 * @mixin IdeHelperAccountAdmin
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $email
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account\AccountUser|null $accountUser
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountAdmin whereUpdatedAt($value)
 */
	class IdeHelperAccountAdmin extends \Eloquent {}
}

namespace App\Models\Account{
/**
 * App\Models\Account\AccountUser
 *
 * @mixin IdeHelperAccountUser
 * @property int $id
 * @property string $username
 * @property string $userable_type
 * @property int $userable_id
 * @property string $password
 * @property array $data
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $userable
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser whereUserableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser whereUserableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountUser whereUsername($value)
 */
	class IdeHelperAccountUser extends \Eloquent {}
}

namespace App\Models\Account{
/**
 * App\Models\Account\BusinessType
 *
 * @mixin IdeHelperBusinessType
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\Multitenancy\TenantCollection|\App\Models\Account\Tenant[] $tenants
 * @property-read int|null $tenants_count
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType query()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessType whereUpdatedAt($value)
 */
	class IdeHelperBusinessType extends \Eloquent {}
}

namespace App\Models\Account{
/**
 * App\Models\Account\Tenant
 *
 * @mixin IdeHelperTenant
 * @property int $id
 * @property string $nickname
 * @property string $name E.g. company name
 * @property string $domain
 * @property string $database
 * @property string $email
 * @property int $business_type_id
 * @property int|null $country_id
 * @property int|null $currency_id
 * @property int|null $language_id
 * @property int|null $timezone_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account\AccountUser|null $accountUser
 * @property-read \Illuminate\Database\Eloquent\Collection|Agent[] $agents
 * @property-read int|null $agents_count
 * @property-read \App\Models\Account\BusinessType $businessType
 * @property-read Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection|Supplier[] $suppliers
 * @property-read int|null $suppliers_count
 * @property-read User|null $user
 * @method static \Spatie\Multitenancy\TenantCollection|static[] all($columns = ['*'])
 * @method static \Spatie\Multitenancy\TenantCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereBusinessTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDatabase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedAt($value)
 */
	class IdeHelperTenant extends \Eloquent {}
}

namespace App\Models\Accounting{
/**
 * App\Models\Accounting\Invoice
 *
 * @mixin IdeHelperInvoice
 * @property int $id
 * @property int $shop_id
 * @property int $customer_id
 * @property int $order_id Main order, usually the only one (used for performance)
 * @property string $number
 * @property string $type
 * @property int $currency_id
 * @property string $exchange
 * @property string $net
 * @property string $total
 * @property string $payment
 * @property string|null $paid_at
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Accounting\InvoiceTransaction[] $invoiceTransactions
 * @property-read int|null $invoice_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $order
 * @property-read int|null $order_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Query\Builder|Invoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereExchange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Invoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Invoice withoutTrashed()
 */
	class IdeHelperInvoice extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Accounting{
/**
 * App\Models\Accounting\InvoiceTransaction
 *
 * @mixin IdeHelperInvoiceTransaction
 * @property int $id
 * @property int $invoice_id
 * @property int|null $transaction_id
 * @property string|null $item_type
 * @property int|null $item_id
 * @property string $quantity
 * @property string $net
 * @property string $discounts
 * @property string $tax
 * @property int|null $tax_band_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $aurora_id
 * @property int|null $aurora_no_product_id
 * @property-read Model|\Eloquent $item
 * @property-read Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereAuroraNoProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereTaxBandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceTransaction whereUpdatedAt($value)
 */
	class IdeHelperInvoiceTransaction extends \Eloquent {}
}

namespace App\Models\Aiku{
/**
 * App\Models\Aiku\Aiku
 *
 * @mixin IdeHelperAiku
 * @property int $id
 * @property string|null $version
 * @property string|null $deployed_at
 * @property array $data
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku query()
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku whereDeployedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aiku whereVersion($value)
 */
	class IdeHelperAiku extends \Eloquent {}
}

namespace App\Models\Aiku{
/**
 * App\Models\Aiku\Deployment
 *
 * @mixin IdeHelperDeployment
 * @property int $id
 * @property string $version
 * @property string $hash
 * @property string $state
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $skip_build
 * @property-read mixed $skip_composer_install
 * @property-read mixed $skip_npm_install
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deployment whereVersion($value)
 */
	class IdeHelperDeployment extends \Eloquent {}
}

namespace App\Models\Assets{
/**
 * App\Models\Assets\CommunalImage
 *
 * @mixin IdeHelperCommunalImage
 * @property int $id
 * @property string $imageable_type
 * @property int $imageable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $imageable
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage whereImageableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage whereImageableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunalImage whereUpdatedAt($value)
 */
	class IdeHelperCommunalImage extends \Eloquent {}
}

namespace App\Models\Assets{
/**
 * App\Models\Assets\Country
 *
 * @mixin IdeHelperCountry
 * @property int $id
 * @property string $code
 * @property string|null $iso3
 * @property string|null $phone_code
 * @property string $name
 * @property string|null $continent
 * @property string|null $capital
 * @property int|null $timezone_id Timezone in capital
 * @property int|null $currency_id
 * @property string|null $type
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Assets\Timezone[] $timezones
 * @property-read int|null $timezones_count
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Query\Builder|Country onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCapital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereContinent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country wherePhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Country withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Country withoutTrashed()
 */
	class IdeHelperCountry extends \Eloquent {}
}

namespace App\Models\Assets{
/**
 * App\Models\Assets\Currency
 *
 * @mixin IdeHelperCurrency
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $symbol
 * @property int $fraction_digits
 * @property string|null $status
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereFractionDigits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 */
	class IdeHelperCurrency extends \Eloquent {}
}

namespace App\Models\Assets{
/**
 * App\Models\Assets\Language
 *
 * @mixin IdeHelperLanguage
 * @property int $id
 * @property string $code
 * @property string|null $name
 * @property string|null $original_name
 * @property string|null $status
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 */
	class IdeHelperLanguage extends \Eloquent {}
}

namespace App\Models\Assets{
/**
 * App\Models\Assets\ProcessedImage
 *
 * @mixin IdeHelperProcessedImage
 * @property-read \App\Models\Assets\CommunalImage|null $communalImage
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcessedImage query()
 */
	class IdeHelperProcessedImage extends \Eloquent {}
}

namespace App\Models\Assets{
/**
 * App\Models\Assets\RawImage
 *
 * @mixin IdeHelperRawImage
 * @property int $id
 * @property string $checksum
 * @property int $filesize
 * @property float $megapixels
 * @property string $mime
 * @property mixed $image_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Assets\CommunalImage|null $communalImage
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereChecksum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereFilesize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereImageData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereMegapixels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawImage whereUpdatedAt($value)
 */
	class IdeHelperRawImage extends \Eloquent {}
}

namespace App\Models\Assets{
/**
 * App\Models\Assets\Timezone
 *
 * @mixin IdeHelperTimezone
 * @property int $id
 * @property string $name
 * @property int|null $offset in hours
 * @property int|null $country_id
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string $location
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Assets\Country[] $countries
 * @property-read int|null $countries_count
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereOffset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereUpdatedAt($value)
 */
	class IdeHelperTimezone extends \Eloquent {}
}

namespace App\Models\Buying{
/**
 * App\Models\Buying\Agent
 *
 * @mixin IdeHelperAgent
 * @property int $id
 * @property string $code
 * @property string $owner_type
 * @property int $owner_id
 * @property string $name
 * @property int $currency_id
 * @property array $settings
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Helpers\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Helpers\ImageModel[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Buying\PurchaseOrder[] $purchaseOrders
 * @property-read int|null $purchase_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Buying\Supplier[] $suppliers
 * @property-read int|null $suppliers_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Agent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent newQuery()
 * @method static \Illuminate\Database\Query\Builder|Agent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Agent withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Agent withoutTrashed()
 */
	class IdeHelperAgent extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Buying{
/**
 * App\Models\Buying\PurchaseOrder
 *
 * @mixin IdeHelperPurchaseOrder
 * @property int $id
 * @property string|null $number
 * @property string $vendor_type
 * @property int $vendor_id
 * @property string $state
 * @property array|null $data
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $submitted_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $vendor
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder newQuery()
 * @method static \Illuminate\Database\Query\Builder|PurchaseOrder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder whereVendorType($value)
 * @method static \Illuminate\Database\Query\Builder|PurchaseOrder withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PurchaseOrder withoutTrashed()
 */
	class IdeHelperPurchaseOrder extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Buying{
/**
 * App\Models\Buying\Supplier
 *
 * @mixin IdeHelperSupplier
 * @property int $id
 * @property string $code
 * @property string $owner_type
 * @property int $owner_id
 * @property string $name
 * @property int $currency_id
 * @property array $settings
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Helpers\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Helpers\ImageModel[] $images
 * @property-read int|null $images_count
 * @property-read Model|\Eloquent $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Buying\PurchaseOrder[] $purchaseOrders
 * @property-read int|null $purchase_orders_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newQuery()
 * @method static \Illuminate\Database\Query\Builder|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Supplier withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Supplier withoutTrashed()
 */
	class IdeHelperSupplier extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\CRM{
/**
 * App\Models\CRM\Customer
 *
 * @mixin IdeHelperCustomer
 * @property int $id
 * @property int|null $shop_id
 * @property string $vendor_type
 * @property int $vendor_id
 * @property string|null $name
 * @property string $status
 * @property string|null $state
 * @property int|null $billing_address_id
 * @property int|null $delivery_address_id null for dropshipping customers
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_customer_id
 * @property int|null $aurora_customer_client_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Basket|null $basket
 * @property-read Address|null $billingAddress
 * @property-read Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection|Customer[] $customers
 * @property-read int|null $customers_count
 * @property-read Address|null $deliveryAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|ImageModel[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read Shop|null $shop
 * @property-read Model|\Eloquent $vendor
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Query\Builder|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAuroraCustomerClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAuroraCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBillingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDeliveryAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereVendorType($value)
 * @method static \Illuminate\Database\Query\Builder|Customer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Customer withoutTrashed()
 */
	class IdeHelperCustomer extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\CustomerProduct
 *
 * @mixin IdeHelperCustomerProduct
 * @property-read Customer $customer
 * @property-read Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerProduct query()
 */
	class IdeHelperCustomerProduct extends \Eloquent {}
}

namespace App\Models\Health{
/**
 * App\Models\Health\Patient
 *
 * @mixin IdeHelperPatient
 * @property int $id
 * @property string $type
 * @property int|null $contact_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection|Contact[] $contacts
 * @property-read int|null $contacts_count
 * @property-read string $age
 * @property-read string $formatted_dob
 * @property-read string $formatted_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Contact[] $guardians
 * @property-read int|null $guardians_count
 * @method static \Database\Factories\Health\PatientFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereUpdatedAt($value)
 */
	class IdeHelperPatient extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Helpers{
/**
 * App\Models\Helpers\Address
 *
 * @mixin IdeHelperAddress
 * @property int $id
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $sorting_code
 * @property string|null $postal_code
 * @property string|null $locality
 * @property string|null $dependant_locality
 * @property string|null $administrative_area
 * @property string|null $country_code
 * @property string|null $checksum
 * @property int|null $owner_id
 * @property string|null $owner_type
 * @property int|null $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Basket[] $baskets
 * @property-read int|null $baskets_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Customer[] $customers
 * @property-read int|null $customers_count
 * @property-read string $formatted_address
 * @property-read Model|\Eloquent $owner
 * @method static \Database\Factories\Helpers\AddressFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAdministrativeArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereChecksum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereDependantLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereSortingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
 */
	class IdeHelperAddress extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Helpers{
/**
 * App\Models\Helpers\Contact
 *
 * @mixin IdeHelperContact
 * @property int $id
 * @property string $contactable_type
 * @property int $contactable_id
 * @property string|null $name
 * @property string|null $company
 * @property string|null $date_of_birth
 * @property string|null $gender
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $website
 * @property string|null $qq
 * @property int|null $address_id
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property string|null $tax_number
 * @property string|null $tax_number_status
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Helpers\Address|null $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $contactable
 * @property-read \Illuminate\Database\Eloquent\Collection|Patient[] $dependants
 * @property-read int|null $dependants_count
 * @property-read string $age
 * @property-read int|float $age_in_years
 * @property-read string $formatted_address
 * @property-read string $formatted_dob
 * @property-read string $formatted_gender
 * @property-read array $gender_icon
 * @method static \Database\Factories\Helpers\ContactFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereContactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereContactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIdentityDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIdentityDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereQq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTaxNumberStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereWebsite($value)
 */
	class IdeHelperContact extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Helpers{
/**
 * App\Models\Helpers\Image
 *
 * @mixin IdeHelperImage
 * @property int $id
 * @property int|null $communal_image_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Helpers\ImageModel[] $models
 * @property-read int|null $models_count
 * @property-read RawImage $rawImage
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCommunalImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 */
	class IdeHelperImage extends \Eloquent {}
}

namespace App\Models\Helpers{
/**
 * App\Models\Helpers\ImageModel
 *
 * @mixin IdeHelperImageModel
 * @property int $id
 * @property int $image_id
 * @property string|null $imageable_type
 * @property int|null $imageable_id
 * @property string $scope
 * @property int $rank
 * @property string $filename
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $aurora_id
 * @property-read \App\Models\Helpers\Image $image
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereImageableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereImageableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel whereUpdatedAt($value)
 */
	class IdeHelperImageModel extends \Eloquent {}
}

namespace App\Models\HumanResources{
/**
 * App\Models\HumanResources\Employee
 *
 * @mixin IdeHelperEmployee
 * @property int $id
 * @property string $nickname
 * @property string|null $worker_number
 * @property int|null $user_id
 * @property string $type
 * @property string $state
 * @property string|null $employment_start_at
 * @property string|null $employment_end_at
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Contact|null $contact
 * @property-read mixed $name
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Query\Builder|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmploymentEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmploymentStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereWorkerNumber($value)
 * @method static \Illuminate\Database\Query\Builder|Employee withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Employee withoutTrashed()
 */
	class IdeHelperEmployee extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Inventory{
/**
 * App\Models\Inventory\Location
 *
 * @mixin IdeHelperLocation
 * @property int $id
 * @property int $warehouse_id
 * @property int|null $warehouse_area_id
 * @property bool $status
 * @property string $state
 * @property string $code
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\Stock[] $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\Inventory\Warehouse $warehouse
 * @property-read \App\Models\Inventory\WarehouseArea|null $warehouseArea
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Query\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereWarehouseAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereWarehouseId($value)
 * @method static \Illuminate\Database\Query\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Location withoutTrashed()
 */
	class IdeHelperLocation extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Inventory{
/**
 * App\Models\Inventory\Stock
 *
 * @mixin IdeHelperStock
 * @property int $id
 * @property string $composition
 * @property string|null $state
 * @property string|null $quantity_status
 * @property bool $sellable
 * @property bool $raw_material
 * @property string $slug
 * @property string $code
 * @property string|null $barcode
 * @property string|null $description
 * @property int|null $pack units per pack
 * @property int|null $outer units per outer
 * @property int|null $carton units per carton
 * @property string|null $quantity stock quantity in units
 * @property float|null $available_forecast days
 * @property string|null $value
 * @property int|null $image_id
 * @property int|null $package_image_id
 * @property array $settings
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|TradeUnit[] $tradeUnits
 * @property-read int|null $trade_units_count
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newQuery()
 * @method static \Illuminate\Database\Query\Builder|Stock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereAvailableForecast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereCarton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereComposition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereOuter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock wherePack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock wherePackageImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereQuantityStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereRawMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereSellable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|Stock withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Stock withoutTrashed()
 */
	class IdeHelperStock extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Inventory{
/**
 * App\Models\Inventory\Warehouse
 *
 * @mixin IdeHelperWarehouse
 * @property int $id
 * @property string $code
 * @property string $name
 * @property array $settings
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\WarehouseArea[] $areas
 * @property-read int|null $areas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\Location[] $locations
 * @property-read int|null $locations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Query\Builder|Warehouse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Warehouse withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Warehouse withoutTrashed()
 */
	class IdeHelperWarehouse extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Inventory{
/**
 * App\Models\Inventory\WarehouseArea
 *
 * @mixin IdeHelperWarehouseArea
 * @property int $id
 * @property int $warehouse_id
 * @property string $code
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \App\Models\Inventory\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea newQuery()
 * @method static \Illuminate\Database\Query\Builder|WarehouseArea onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea query()
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseArea whereWarehouseId($value)
 * @method static \Illuminate\Database\Query\Builder|WarehouseArea withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WarehouseArea withoutTrashed()
 */
	class IdeHelperWarehouseArea extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\LocationStock
 *
 * @mixin IdeHelperLocationStock
 * @property-read Location $location
 * @property-read Stock $stock
 * @method static \Illuminate\Database\Eloquent\Builder|LocationStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationStock query()
 */
	class IdeHelperLocationStock extends \Eloquent {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\Adjust
 *
 * @mixin IdeHelperAdjust
 * @property int $id
 * @property int|null $shop_id
 * @property string $type
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $slug_source
 * @property-read Shop|null $shop
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust query()
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjust whereUpdatedAt($value)
 */
	class IdeHelperAdjust extends \Eloquent {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\Basket
 *
 * @property mixed $alt_delivery_address_id
 * @property mixed $deliveryAddress
 * @mixin IdeHelperBasket
 * @property int $id
 * @property int|null $shop_id
 * @property int $customer_id
 * @property string|null $nickname
 * @property string $state
 * @property int $items number of items
 * @property string $items_discounts
 * @property string $items_net
 * @property string $charges
 * @property string|null $shipping
 * @property string $net
 * @property string $tax
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $aurora_id
 * @property-read Customer $customer
 * @property-read int|null $delivery_address_count
 * @property-read Shop|null $shop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sales\BasketTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Basket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Basket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Basket query()
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereItemsDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereItemsNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Basket whereUpdatedAt($value)
 */
	class IdeHelperBasket extends \Eloquent {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\BasketTransaction
 *
 * @mixin IdeHelperBasketTransaction
 * @property int $id
 * @property int $basket_id
 * @property string|null $item_type
 * @property int|null $item_id
 * @property string $quantity
 * @property string $discounts
 * @property string $net
 * @property int|null $tax_band_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $aurora_id
 * @property-read Model|\Eloquent $item
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereBasketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereTaxBandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasketTransaction whereUpdatedAt($value)
 */
	class IdeHelperBasketTransaction extends \Eloquent {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\Charge
 *
 * @mixin IdeHelperCharge
 * @property int $id
 * @property int|null $shop_id
 * @property bool $status
 * @property string $type
 * @property string $slug
 * @property string $name
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read string $slug_source
 * @property-read Shop|null $shop
 * @method static \Illuminate\Database\Eloquent\Builder|Charge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge newQuery()
 * @method static \Illuminate\Database\Query\Builder|Charge onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge query()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Charge withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Charge withoutTrashed()
 */
	class IdeHelperCharge extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\Order
 *
 * @mixin IdeHelperOrder
 * @property int $id
 * @property int $shop_id
 * @property int $customer_id
 * @property int|null $customer_client_id
 * @property string|null $number
 * @property string $state
 * @property int $items number of items
 * @property string $items_discounts
 * @property string $items_net
 * @property int $currency_id
 * @property string $exchange
 * @property string $charges
 * @property string|null $shipping
 * @property string $net
 * @property string $tax
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Customer $customer
 * @property-read Customer|null $customerClient
 * @property-read \Illuminate\Database\Eloquent\Collection|InvoiceTransaction[] $invoiceTransactions
 * @property-read int|null $invoice_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Invoice[] $invoices
 * @property-read int|null $invoices_count
 * @property-read Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sales\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereExchange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereItemsDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereItemsNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 */
	class IdeHelperOrder extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\ShippingSchema
 *
 * @mixin IdeHelperShippingSchema
 * @property int $id
 * @property int|null $shop_id
 * @property bool $status
 * @property string $slug
 * @property string $type
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read string $slug_source
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sales\ShippingZone[] $shippingZone
 * @property-read int|null $shipping_zone_count
 * @property-read Shop|null $shop
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema newQuery()
 * @method static \Illuminate\Database\Query\Builder|ShippingSchema onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingSchema whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ShippingSchema withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ShippingSchema withoutTrashed()
 */
	class IdeHelperShippingSchema extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\ShippingZone
 *
 * @mixin IdeHelperShippingZone
 * @property int $id
 * @property int|null $shipping_schema_id
 * @property bool $status
 * @property string $slug
 * @property string $code
 * @property string $name
 * @property int $rank
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read string $slug_source
 * @property-read \App\Models\Sales\ShippingSchema|null $shippingSchema
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone newQuery()
 * @method static \Illuminate\Database\Query\Builder|ShippingZone onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereShippingSchemaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingZone whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ShippingZone withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ShippingZone withoutTrashed()
 */
	class IdeHelperShippingZone extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\TaxBand
 *
 * @mixin IdeHelperTaxBand
 * @property int $id
 * @property bool $status
 * @property string $code
 * @property string $type
 * @property string $type_name
 * @property string $name
 * @property int|null $country_id
 * @property string $rate
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand newQuery()
 * @method static \Illuminate\Database\Query\Builder|TaxBand onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxBand whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|TaxBand withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TaxBand withoutTrashed()
 */
	class IdeHelperTaxBand extends \Eloquent {}
}

namespace App\Models\Sales{
/**
 * App\Models\Sales\Transaction
 *
 * @mixin IdeHelperTransaction
 * @property int $id
 * @property int $order_id
 * @property string|null $item_type
 * @property int|null $item_id
 * @property string $quantity
 * @property string $discounts
 * @property string $net
 * @property int|null $tax_band_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property int|null $aurora_no_product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|InvoiceTransaction[] $invoiceTransactions
 * @property-read int|null $invoice_transactions_count
 * @property-read Model|\Eloquent $item
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|Transaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAuroraNoProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTaxBandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Transaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Transaction withoutTrashed()
 */
	class IdeHelperTransaction extends \Eloquent {}
}

namespace App\Models\System{
/**
 * App\Models\System\Permission
 *
 * @mixin IdeHelperPermission
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\System\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\System\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 */
	class IdeHelperPermission extends \Eloquent {}
}

namespace App\Models\System{
/**
 * App\Models\System\Role
 *
 * @mixin IdeHelperRole
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\System\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\System\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class IdeHelperRole extends \Eloquent {}
}

namespace App\Models\System{
/**
 * App\Models\System\User
 *
 * @mixin IdeHelperUser
 * @property int $id
 * @property string|null $username
 * @property string $password
 * @property string $userable_type
 * @property int $userable_id
 * @property bool $status
 * @property int $language_id
 * @property int $timezone_id
 * @property array $data
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $aurora_id
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Helpers\ImageModel[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\System\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\System\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\System\UserStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tenant\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $userable
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class IdeHelperUser extends \Eloquent {}
}

namespace App\Models\System{
/**
 * App\Models\System\UserStats
 *
 * @mixin IdeHelperUserStats
 * @property-read \App\Models\System\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats query()
 */
	class IdeHelperUserStats extends \Eloquent {}
}

namespace App\Models\Tenant{
/**
 * App\Models\Tenant\PersonalAccessToken
 *
 * @mixin IdeHelperPersonalAccessToken
 * @property int $id
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property string $name
 * @property string $token
 * @property array|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $tokenable
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereUpdatedAt($value)
 */
	class IdeHelperPersonalAccessToken extends \Eloquent {}
}

namespace App\Models\Trade{
/**
 * App\Models\Trade\HistoricProduct
 *
 * @mixin IdeHelperHistoricProduct
 * @property int $id
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $product_id
 * @property string $price unit price
 * @property string|null $code
 * @property string|null $name
 * @property int|null $pack units per pack
 * @property int|null $outer units per outer
 * @property int|null $carton units per carton
 * @property string|null $cbm to be deleted
 * @property int|null $currency_id
 * @property int|null $aurora_product_id
 * @property int|null $aurora_supplier_product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Trade\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct newQuery()
 * @method static \Illuminate\Database\Query\Builder|HistoricProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereAuroraProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereAuroraSupplierProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereCarton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereCbm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereOuter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct wherePack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoricProduct whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|HistoricProduct withTrashed()
 * @method static \Illuminate\Database\Query\Builder|HistoricProduct withoutTrashed()
 */
	class IdeHelperHistoricProduct extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Trade{
/**
 * App\Models\Trade\Product
 *
 * @mixin IdeHelperProduct
 * @property int $id
 * @property string $composition
 * @property string|null $slug
 * @property string $vendor_type
 * @property int $vendor_id
 * @property string|null $state
 * @property bool|null $status
 * @property string $code
 * @property string|null $name
 * @property string|null $description
 * @property string $price unit price
 * @property int|null $pack units per pack
 * @property int|null $outer units per outer
 * @property int|null $carton units per carton
 * @property int|null $available
 * @property int|null $image_id
 * @property array $settings
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_product_id
 * @property int|null $aurora_supplier_product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read string $slug_source
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Trade\HistoricProduct[] $historicRecords
 * @property-read int|null $historic_records_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Helpers\ImageModel[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Trade\TradeUnit[] $tradeUnits
 * @property-read int|null $trade_units_count
 * @property-read Model|\Eloquent $vendor
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAuroraProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAuroraSupplierProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCarton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereComposition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOuter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVendorType($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 */
	class IdeHelperProduct extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Trade{
/**
 * App\Models\Trade\Shop
 *
 * @mixin IdeHelperShop
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $state
 * @property string $type
 * @property string|null $open_at
 * @property string|null $closed_at
 * @property int $language_id
 * @property int $currency_id
 * @property int $timezone_id
 * @property array $data
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Adjust[] $adjusts
 * @property-read int|null $adjusts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Charge[] $charges
 * @property-read int|null $charges_count
 * @property-read Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection|Customer[] $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Trade\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ShippingSchema[] $shippingSchema
 * @property-read int|null $shipping_schema_count
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newQuery()
 * @method static \Illuminate\Database\Query\Builder|Shop onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereOpenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Shop withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Shop withoutTrashed()
 */
	class IdeHelperShop extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models\Trade{
/**
 * App\Models\Trade\TradeUnit
 *
 * @mixin IdeHelperTradeUnit
 * @property int $id
 * @property string|null $slug
 * @property string $code
 * @property string|null $name
 * @property string|null $description
 * @property string|null $barcode
 * @property float|null $weight
 * @property array|null $dimensions
 * @property string|null $type unit type
 * @property int|null $image_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $aurora_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Trade\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Stock[] $stocks
 * @property-read int|null $stocks_count
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit newQuery()
 * @method static \Illuminate\Database\Query\Builder|TradeUnit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereAuroraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradeUnit whereWeight($value)
 * @method static \Illuminate\Database\Query\Builder|TradeUnit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TradeUnit withoutTrashed()
 */
	class IdeHelperTradeUnit extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

