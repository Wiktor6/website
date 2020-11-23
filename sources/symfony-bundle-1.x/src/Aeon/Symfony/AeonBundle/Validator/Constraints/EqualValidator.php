<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Month;
use Aeon\Calendar\Gregorian\Year;
use Symfony\Component\Validator\Constraints\AbstractComparisonValidator;

final class EqualValidator extends AbstractComparisonValidator
{
    /**
     * @param DateTime|Day|Month|Year     $value1
     * @param ?DateTime|?Day|?Month|?Year $value2
     */
    protected function compareValues($value1, $value2): bool
    {
        if (!$value2 instanceof DateTime && !$value2 instanceof Day && !$value2 instanceof Month && !$value2 instanceof Year) {
            return false;
        }

        if (\get_class($value1) !== \get_class($value2)) {
            return false;
        }

        return $value1->isEqual($value2);
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorCode(): string
    {
        return Equal::NOT_EQUAL_ERROR;
    }
}
