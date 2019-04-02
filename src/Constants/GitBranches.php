<?php

namespace VIITech\Helpers\Constants;

use BenSampo\Enum\Enum;

abstract class GitBranches extends Enum
{
    const GIT_BRANCH_DEV = "dev";
    const GIT_BRANCH_STAGING = "staging";
    const GIT_BRANCH_BETA = "beta";
    const GIT_BRANCH_MASTER = "master";
}