<?php

namespace Santosh\Sambat;

use Santosh\Sambat\Exceptions\DateException;

class Converter
{
    private $nepaliDate;

    private $engDate;

    private $nepDaysMonthsYears;

    private $config;

    private $nepMonths;

    private $nepDayOfWeek;

    private $startEng;

    private $startNep;

    private $error;

    public function __construct()
    {
        $this->config = new Config\Config();

        $this->nepDaysMonthsYears = $this->config->nepDaysMonthsYears;

        $this->engDaysMonths = $this->config->engDaysMonths;

        $this->engDaysLeapYear = $this->config->engDaysLeapYear;

        $this->nepMonths = $this->config->mahina;

        $this->nepDayOfWeek = $this->config->bars;

        $this->startNep = min(array_keys($this->config->nepDaysMonthsYears));

        $this->startEng = $this->startNep - 56;
    }

    /**
     * @param $date
     * @return array
     */
    private function separate($date)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            // if date format is y-m-d
            $dates = explode('-', $date);
        } elseif (preg_match("/^[0-9]{4}[-.\/ -](0[1-9]|1[0-2])[-.\/ -](0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            // if date format is y/m/d
            $dates = explode('/', $date);
        } else {
            $this->error = "Invalid date format";
        }

        return [
            'y' => (int)(isset($dates[0]) ? $dates[0] : null),
            'm' => (int)(isset($dates[1]) ? $dates[1] : null),
            'd' => (int)(isset($dates[1]) ? $dates[1] : null)
        ];

    }

    private function isInRangeEng($date)
    {
        $minYear = min(array_keys($this->nepDaysMonthsYears));

        $maxYear = max(array_keys($this->nepDaysMonthsYears));

        if ($date['y'] < $minYear || $date['y'] > $maxYear) {
            $this->error = "Date range error. Supported only between $minYear - $maxYear";

            return false;
        }

        return true;
    }

    private function isLeapYear($year)
    {
        if ($year % 100 === 0) {
            if ($year % 400 == 0) {
                return true;
            } else {
                return false;
            }

        } else {
            if ($year % 4 == 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param $date
     * @throws DateException
     */
    public function adToBs($date)
    {
        $date = array_filter($this->separate($date));

        if (count($date) < 3) {
            throw new DateException($this->error);
        }

        if ($this->isInRangeEng($date)) {
            $startNepMonth = 9;
            $startNepDay = 16;

            $totalEngDays = 0;
            $day = 6;

            // count total no. of days in year
            for ($i = 0; $i < ($date['y'] - $this->startEng); $i++) {
                if ($this->isLeapYear($this->startEng + $i) === true) {
                    for ($j = 0; $j < 12; $j++)
                        $totalEngDays += $this->engDaysLeapYear[$j];
                } else {
                    for ($j = 0; $j < 12; $j++) {
                        $totalEngDays += $this->engDaysMonths[$j];
                    }
                }
            }

            // count total no. of days in months

            for ($i = 0; $i < ($date['m'] - 1); $i++) {
                if ($this->isLeapYear($date['y']) === true) {
                    $totalEngDays += $this->engDaysLeapYear[$i];
                } else {
                    $totalEngDays += $this->engDaysMonths[$i];
                }
            }

            $totalEngDays += $date['d'];

            $i = $this->startNep; $j = $startNepMonth;
            $totalNepDays = $startNepDay;
            $month = $startNepMonth;
            $year = $this->startNep;


            // count nepali date from array
            while($totalEngDays != 0) {
                $a = $this->nepDaysMonthsYears[$i][$j - 1];
                $totalNepDays++;
                $day++;								//count the days in 7 days, 1 week
                if($totalNepDays > $a){
                    $month++;
                    $totalNepDays=1;
                    $j++;
                }
                if($day > 7)
                    $day = 1;
                if($month > 12){
                    $year++;
                    $month = 1;
                }
                if($j > 12){
                    $j = 1; $i++;
                }
                $totalEngDays--;
            }

            $this->nepaliDate =  array(
                'y' => $year,
                'm' => $month,
                'd' => $day
            );

            return $this->nepaliDate;
        }


        throw new DateException($this->error);
    }


    private function getNepaliMonth($month)
    {
        // array starts with index 0
        if (isset($this->nepMonths[$month - 1])) {
            return $this->nepMonths[$month - 1];
        }
    }

    private function getNepaliBar($day)
    {
        // array starts with index 0
        if (isset($this->nepMonths[$day - 1])) {
            return $this->nepMonths[$day - 1];
        }
    }
}