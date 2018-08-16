# laravel-dev-helpers
 Laravel Dev Helper Functions
 
## Installation
 `composer require viitech/laravel-dev-helpers:master`
 
## Middleware
 - CORS Middleware `\VIITech\Helpers\Middleware\CorsMiddleware::class`
 
## Console

### Dingo API Routes List 
1. Add `\VIITech\Helpers\Console\DingoRoutesList::class` to `Kernal.php`
2. Run `php artisan route:list && php artisan api:routes`
 
## Packagist

### [Lumen Routes List](https://packagist.org/packages/appzcoder/lumen-routes-list)
1. Register service provider in bootstrap.app `\VIITech\Helpers\Packagist\LumenRoutesListHelper::registerServiceProvider($app)`
2. Run `composer dump-autoload && php artisan route:list`

### [Laravel Tinker](https://packagist.org/packages/laravel/tinker)
1. Register service provider in bootstrap.app `\VIITech\Helpers\Packagist\TinkerHelper::registerServiceProvider($app)`
2. Run `php artisan tinker`

### [Sentry](https://packagist.org/packages/sentry/sentry-laravel)
1. Register service provider in bootstrap.app `\VIITech\Helpers\Packagist\SentryHelper::registerServiceProvider($app)`
2. Add `SentryHelper::capture($this, $e);` to `Handler.php`

### [Security Checker](https://packagist.org/packages/sensiolabs/security-checker)
1. Run `php vendor/sensiolabs/security-checker/security-checker security:check`

### [Lumen Vendor Publish](https://packagist.org/packages/laravelista/lumen-vendor-publish)
1. Add `\Laravelista\LumenVendorPublish\VendorPublishCommand::class` to `app/Console/Kernel.php`
2. Run `php artisan vendor:publish`

## Dingo API and JWT

### Enable custom array serializer
1. Add `CustomDingoSerializerProvider::class` to Service Provider in `bootstrap/app.php`
2. Add `API_CUSTOM_SERIALIZER=true` to `.env`

### Enable JWT
1. Add `CustomDingoSerializerProvider::class` to Service Provider in `bootstrap/app.php`
2. Add `API_ENABLE_JWT=true` to `.env`

## Functions

### Global Helpers
- Check App Environment `\VIITech\Helpers\GlobalHelpers::checkEnvironment()`
- Check Is Development Environment `\VIITech\Helpers\GlobalHelpers::isDevelopmentEnv()`
- Get Binary Path `\VIITech\Helpers\GlobalHelpers::getBinaryPath()`
- Is Valid Object `\VIITech\Helpers\GlobalHelpers::isValidObject()`
- Return String `\VIITech\Helpers\GlobalHelpers::returnString()`
- Return Boolean `\VIITech\Helpers\GlobalHelpers::returnBoolean()`
- Convert String Array To Integer Array `\VIITech\Helpers\GlobalHelpers::convertStringArrayToIntegerArray()`
- Convert Comma Separated String to Array `\VIITech\Helpers\GlobalHelpers::convertCommaSeparatedStringToArray()`
- Get Readable Boolean `\VIITech\Helpers\GlobalHelpers::getReadableBoolean()`
- Run Shell Command `\VIITech\Helpers\GlobalHelpers::runShellCommand()`
- Run Command In Server `\VIITech\Helpers\GlobalHelpers::runCommandInServer()`
- Validate Variable with Alternative `\VIITech\Helpers\GlobalHelpers::validateVarWithAlternative()`
- Return Response (Array) `\VIITech\Helpers\GlobalHelpers::returnResponse()`
- Generate Random Number `\VIITech\Helpers\GlobalHelpers::generateRandomNumber()`
- Get Page Title From URL `\VIITech\Helpers\GlobalHelpers::getPageTitle()`
- Is String English? `\VIITech\Helpers\GlobalHelpers::isEnglish()`
- URL Exists? `\VIITech\Helpers\GlobalHelpers::urlExists()`
- Get Web Page Content `\VIITech\Helpers\GlobalHelpers::getWebPageContent()`
- Check if variable is valid `\VIITech\Helpers\GlobalHelpers::isValidVariable()`
- Return value from nullable object `\VIITech\Helpers\GlobalHelpers::returnValueFromNullableObject()`
- Return value from nullable object `\VIITech\Helpers\GlobalHelpers::getUniqueIDsFromArray()`
- Return Integer `\VIITech\Helpers\GlobalHelpers::returnInteger()`
- Hash Password `\VIITech\Helpers\GlobalHelpers::hashPassword()`
- Convert Hex to RGB `\VIITech\Helpers\GlobalHelpers::hex2rgb()`
- set Laravel Environment Value `\VIITech\Helpers\GlobalHelpers::setLaravelEnvironmentValue()`

### Google Helpers
- Validate Google reCaptcha `\VIITech\Helpers\GoogleHelpers::validateRecaptcha()`
- Validate Google Token `\VIITech\Helpers\GoogleHelpers::validateGoogleToken()`

### Gitlab Helpers
- List All Gitlab Projects `\VIITech\Helpers\GitlabHelpers::listGitlabProjects()`
- List Gitlab Issues `\VIITech\Helpers\GitlabHelpers::listGitlabIssues()`
- Create Gitlab Issue `\VIITech\Helpers\GitlabHelpers::createGitlabIssue()`
- Close Gitlab Issue `\VIITech\Helpers\GitlabHelpers::closeGitlabIssue()`
- Delete Gitlab Issue `\VIITech\Helpers\GitlabHelpers::deleteGitlabIssue()`

### Slack Helpers
- Send Slack Message `\VIITech\Helpers\SlackHelpers::sendSlackMessage()`
- Send Slack Message with Details `\VIITech\Helpers\SlackHelpers::sendSlackWithDetails()`

### Firebase Helpers
- Generate Dynamic Link `\VIITech\Helpers\FirebaseHelpers::generateDynamicLink()`
- Send Firebase Cloud Message `\VIITech\Helpers\FirebaseHelpers::sendFCM()`