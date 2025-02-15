<?php

namespace App\Facade;

final class YearMonthFacade
{
    private $months = [];
    private $years = [];

    public function __construct($buildMonth = true)
    {
        if (true === $buildMonth) {
            $this->buildMonths();
        }
    }

    /**
     * @param int $limit
     */
    private function buildMonths($limit = 12)
    {
        for ($i = 1; $i <= 12; $i++) {
            if ($i > $limit) {
                continue;
            }

            $month = \DateTime::createFromFormat(
                'md',
                str_pad($i . '01', 4, '0', STR_PAD_LEFT)
            );
            $this->months[$month->format('F')] = $i;
        }
    }

    /**
     * @param int $begin
     * @param int|null $end
     * @param bool $reverse
     * @throws \Exception
     */
    private function buildYears($begin, $end = null, $reverse = true)
    {
        if (null === $end) {
            $now = new \DateTime();
            $end = $now->format('Y');
        }

        for (; (int) $begin <= (int) $end; $begin++) {
            $this->years[$begin] = $begin;
        }

        if (true === $reverse) {
            arsort($this->years);
        }
    }

    /**
     * @return array
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @param $begin
     * @param null $end
     * @param bool $reverse
     * @return array
     * @throws \Exception
     */
    public function getYears($begin, $end = null, $reverse = true)
    {
        $this->buildYears($begin, $end, $reverse);
        return $this->years;
    }

    /**
     * @return array
     */
    public static function getSnapshotMonths()
    {
        $self = new self();
        return array_slice($self->getMonths(), 0, null, true);
    }

    /**
     * @param $begin
     * @param null $end
     * @param bool $reverse
     * @return array
     * @throws \Exception
     */
    public static function getSnapshotYears($begin, $end = null, $reverse = true)
    {
        $self = new self(false);
        return array_slice($self->getYears($begin, $end, $reverse), 0, null, true);
    }
}