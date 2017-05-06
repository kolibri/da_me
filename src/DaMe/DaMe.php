<?php

namespace DaMe;

class DaMe
{
    const FORMAT_MONTH_DAY = 'm-d';
    const FORMAT_YEAR_MONTH_DAY = 'Y-m-d';

    /** @var  Loader */
    private $loader;
    private $dateMessages;

    public function __construct(Loader $loader, $data)
    {
        $this->loader = $loader;
        foreach ($this->loader->loadDataPath($data) as $dateMessage) {
            $this->addDateMessage($dateMessage);
        }
    }

    public function getRandomDateMessage(\DateTime $date)
    {
        $messages = $this->getDateMessages($date);
        $rand = array_rand($messages); // Leave this for debugging ;)
        return $messages[$rand];
    }

    public function getDateMessages(\DateTime $date)
    {
        $monthDay = $date->format(self::FORMAT_MONTH_DAY);

        if (!isset($this->dateMessages[$monthDay])) {
            throw new NotFoundException(sprintf('no entries for date %s', $monthDay));
        }

        return $this->dateMessages[$monthDay];
    }

    private function addDateMessage(DateMessage $dateMessage)
    {
        $monthDay = sprintf('%s-%s', $dateMessage->getMonth(), $dateMessage->getDay());
        $this->dateMessages[$monthDay][] = $dateMessage;
    }
}
