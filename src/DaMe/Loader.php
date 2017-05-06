<?php

namespace DaMe;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class Loader
{
    public function loadDataPath($path)
    {
        $finder = new Finder();
        $finder->files()->in($path)->name('/\.yml$/');

        $buffer = [];

        foreach ($finder as $item) {
            $buffer = array_merge(
                $buffer,
                Yaml::parse(file_get_contents(
                    $item->getRealPath()
                ))
            );
        }

        return $this->flattenRawArray($buffer);
    }

    private function flattenRawArray(array $raw)
    {
        $buffer = [];
        foreach ($raw as $monthDay => $dateMessages) {
            foreach ($dateMessages as $dateMessage) {
                foreach ($dateMessage as $year => $message) {
                    $buffer[] = new DateMessage(
                        \DateTime::createFromFormat('Y-m-d', $year.'-'.$monthDay),
                        $message
                    );
                }
            }
        }

        return $buffer;
    }
}