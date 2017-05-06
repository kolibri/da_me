<?php

namespace DaMe;

class DaMe
{
    const FORMAT_MONTH_DAY = 'm-d';

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

    /**
     * @param \DateTime $date
     * @return DateMessage
     */
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
        $this->dateMessages[$dateMessage->getDate()->format(self::FORMAT_MONTH_DAY)][] = $dateMessage;
    }
}
