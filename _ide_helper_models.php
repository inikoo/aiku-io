<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Aiku{
/**
 * App\Models\Aiku\BusinessType
 *
 * @mixin IdeHelperBusinessType
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\Multitenancy\TenantCollection|\App\Models\Aiku\Tenant[] $tenants
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

namespace App\Models\Aiku{
/**
 * App\Models\Aiku\Tenant
 *
 * @mixin IdeHelperTenant
 * @property int $id
 * @property string $name
 * @property string $domain
 * @property string $database
 * @property int $business_type_id
 * @property int|null $country_id
 * @property int|null $currency_id
 * @property int|null $language_id
 * @property int|null $timezone_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Aiku\BusinessType $business_type
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
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedAt($value)
 */
	class IdeHelperTenant extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Assets\Timezone[] $timezones
 * @property-read int|null $timezones_count
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCapital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereContinent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country wherePhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
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

namespace App\Models\Health{
/**
 * App\Models\Health\Patient
 *
 * @mixin IdeHelperPatient
 * @property int $id
 * @property string $name
 * @property string|null $date_of_birth
 * @property string|null $gender
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Contact[] $contacts
 * @property-read int|null $contacts_count
 * @method static \Database\Factories\Health\PatientFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereIdentityDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereIdentityDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereUpdatedAt($value)
 */
	class IdeHelperPatient extends \Eloquent {}
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
 * @property string|null $dependent_locality
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
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereDependentLocality($value)
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
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Helpers\Address|null $address
 * @property-read \Illuminate\Database\Eloquent\Collection|Patient[] $patients
 * @property-read int|null $patients_count
 * @method static \Database\Factories\Helpers\ContactFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIdentityDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIdentityDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 */
	class IdeHelperContact extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @mixin IdeHelperUser
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class IdeHelperUser extends \Eloquent {}
}

