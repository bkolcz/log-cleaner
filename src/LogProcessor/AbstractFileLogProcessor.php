<?php

namespace LogCleaner\LogProcessor;

abstract class AbstractFileLogProcessor implements LogProcessorInterface
{

    // TODO to reshape
    public function processFileStream(string $inputFilePath, string $outputFilePath, mixed $strategyCallback, array $args = [])
    {
        // $options = getopt("f:d:");

        // // var_dump($argv);
        // $headers = [
        //     "0" => "ip",
        //     "1" => "unknown",
        //     "2" => "unknown-2",
        //     "3" => "date",
        //     "5" => "request",
        //     "6" => "ams"
        // ];
        // $older = key_exists("d", $options) ? new \DateTime($options["d"]) : null;
        $fn = fopen($inputFilePath, "r");
        // var_dump(feof($fn)); // DEBUG
        $dateColumn = -1;
        while (!feof($fn)) {
            $dateColumnValidation = [];
            $recordTimestamp = null;
            $line = preg_replace("/(?<=[0-9]) (?=[\+-][0-9]{4}\])/", "_", fgets($fn));
            if (empty($line)) continue;
            // $result = fgetcsv($fn, separator: " ");
            $result = str_getcsv($line, separator: " ");
            if ($dateColumn < 0) {
                foreach ($result as $index => $value) {
                    try {
                        $timestamp = empty($value) ? null : new \DateTime(str_replace(["[", "]", "_"], " ", $value));
                    } catch (\Exception $e) {
                        $timestamp = null;
                    }
                    if ($dateColumn < 0 && $timestamp?->getTimestamp() > 0) {
                        array_push($result, $value);
                        $dateColumn = $index;
                        $recordTimestamp = $timestamp;
                        // break;
                    }
                    array_push($dateColumnValidation, $timestamp?->getTimestamp());
                }
                // array_push($result, implode(",", $dateColumnValidation));
                array_push($result, "time at [$dateColumn]");
            } else {
                try {
                    $value = $result[$dateColumn];
                    // var_dump($value);
                    $recordTimestamp = empty($value) ? null : new \DateTime(str_replace(["[", "]", "_"], " ", $value));
                    // var_dump($recordTimestamp);
                } catch (\Exception $e) {
                    $recordTimestamp = null;
                }
                // array_push($dateColumnValidation, $recordRimestamp?->getTimestamp());
                // array_push($result, implode(",", $dateColumnValidation));
                array_push($result, "time at [$dateColumn]");
            }
            $pattern = str_repeat("%s****", count($result));
            // echo implode("****",$result) . "\n";
            // var_dump($recordTimestamp);
            if (!empty($older)) {
                // var_dump($recordTimestamp?->getTimestamp(), $older?->getTimestamp());
                if ($recordTimestamp?->getTimestamp() > $older?->getTimestamp()) {
                    $result[$dateColumn] = preg_replace("/(?<=[0-9])_(?=[\+-][0-9]{4}\])/", " ", $result[$dateColumn]);
                    printf("$pattern\n", ...$result);
                } else {
                    // printf("$pattern\n", ...$result);
                }
            } else {
                printf("$pattern\n", ...$result);
            }
        }

        fclose($fn);
    }
}
