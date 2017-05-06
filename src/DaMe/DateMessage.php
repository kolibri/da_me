<?php

namespace DaMe;

class DateMessage
{
    /** @var \DateTime  */
    private $date;
    private $message;

    public function __construct(\DateTime $date, $message)
    {
        $this->date = $date;
        $this->message = $message;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function format(\DateTime $date)
    {
        return sprintf(
            $this->getMessage(),
            $date->diff($this->date)->y
        );
    }
}