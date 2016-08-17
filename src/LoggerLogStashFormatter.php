<?php

namespace Groar\JiraAuthentication;

class LoggerLogStashFormatter extends \Monolog\Formatter\LogstashFormatter {
    public function format(array $record) {
        $result = parent::format($this->convertObjectToArray($record, array('.', '*DOT*')));
        return $result;

    }

    private function convertObjectToArray($object, array $keyReplacements = array())
    {
        if (is_object($object)) {
            $object = (array)$object;
        }

        if (is_array($object)) {
            $new = array();
            foreach ($object AS $key => $value) {
                $key = str_replace(array_keys($keyReplacements), array_values($keyReplacements), $key);
                $new[$key] = $this->convertObjectToArray($value);
            }
        } else {
            $new = $object;
        }
        return $new;
    }
}



?>