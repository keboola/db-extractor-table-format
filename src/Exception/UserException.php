<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TableResultFormat\Exception;

use Exception;
use Keboola\CommonExceptions\UserExceptionInterface;

class UserException extends Exception implements UserExceptionInterface
{

}
