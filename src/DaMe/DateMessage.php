<?php

namespace DaMe;

class DateMessage
{
    private $month;
    private $day;
    private $message;

    public function __construct($month, $day, $message)
    {
        $this->month = $month;
        $this->day = $day;
        $this->message = $message;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function format(\DateTime $date)
    {
        $replace = [];

        if (preg_match_all('/\{\d\d\d\d\}/', $this->message, $yearTokens)) {
            foreach ($yearTokens[0] as $yearToken) {
                $year = substr($yearToken, 1, -1);
                $messageDate = \DateTime::createFromFormat(
                    DaMe::FORMAT_YEAR_MONTH_DAY,
                    sprintf('%s-%s-%s', $year, $this->month, $this->day)
                );

                $replace[$yearToken] = $date->diff($messageDate)->y;
            }
        }

        return strtr(
            $this->getMessage(),
            $replace
        );
    }
}