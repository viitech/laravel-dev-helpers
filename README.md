# laravel-dev-helpers
 Laravel Dev Helper Functions
 
 ## Installation
 `composer require viitech/laravel-dev-helpers:master`

## Functions

### Global Helpers
- Check App Environment `\VIITech\Helpers\GlobalHelpers::checkEnvironment()`
- Get Binary Path `\VIITech\Helpers\GlobalHelpers::getBinaryPath()`
- Is Valid Object `\VIITech\Helpers\GlobalHelpers::isValidObject()`
- Return String `\VIITech\Helpers\GlobalHelpers::returnString()`
- Return Boolean `\VIITech\Helpers\GlobalHelpers::returnBoolean()`
- Convert String Array To Integer Array `\VIITech\Helpers\GlobalHelpers::convertStringArrayToIntegerArray()`
- Convert Comma Separated String to Array `\VIITech\Helpers\GlobalHelpers::convertCommaSeparatedStringToArray()`
- Get Readable Boolean `\VIITech\Helpers\GlobalHelpers::getReadableBoolean()`
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