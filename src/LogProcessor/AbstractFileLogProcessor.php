<?php

namespace LogCleaner\LogProcessor;

use LogCleaner\LogConfig\LogConfigInterface;
use InvalidArgumentException;

abstract class AbstractFileLogProcessor implements LogProcessorInterface
{

    public function __construct(protected LogConfigInterface $config)
    {
    }

    public function remove(array $config = []): mixed
    {
        return ["Not implemented yet"];
    }
    public function removeAll(array $config = [
        "inputFile" => null,
        "outputFile" => null,
        "stdOut" => null,
        "dateFrom" => null,
        "dateTo" => null,
        "spaceRegex" => null,
        "spaceRegexRevert" => null,
        "spaceMockCharacter" => null,
        "delimiter" => null,
        "enclosure" => null,
        "replaceDateChars" => [
            "from" => [],
            "to" => []
        ],
        "dateColumnIndex" => -1
    ]): mixed
    {
        extract($config);
        $inputFile = $inputFile ?? $this->config?->inputFile ?? "";
        $outputFile = $outputFile ?? $this->config?->outputFile ?? "{$inputFile}_tmp.log";
        $stdOut = $stdOut ?? $this->config?->$stdOut ?? null;
        $dateFrom = $dateFrom ?? $this->config?->dateFrom ?? null;
        $dateTo = $dateTo ?? $this->config?->dateTo ?? null;
        $spaceRegex = $spaceRegex ?? $this->config?->dateFilter["spaceRegex"] ?? null;
        $spaceRegexRevert = $spaceRegexRevert ?? $this->config?->dateFilter["spaceRegexRevert"] ?? null;
        $spaceMockCharacter = $spaceMockCharacter ?? $this->config?->dateFilter["spaceMockCharacter"] ?? null;
        $delimiter = $delimiter ?? $this->config?->delimiter ?? " ";
        $enclosure = $enclosure ?? $this->config?->enclosure ?? null;
        $replaceDateChars = $replaceDateChars ?? $this->config?->dateFilter["replaceDateChars"] ?? [
            "from" => [],
            "to" => []
        ];
        $dateColumnIndex = $dateColumnIndex ?? $this->config?->dateColumnIndex ?? -1;
        $overrideFile = false;
        $response = [];
        if (empty($inputFile)) throw new InvalidArgumentException("No input file");
        if ($outputFile == $inputFile) {
            $overrideFile = true;
            $outputFile = "{$outputFile}_tmp.log";
        }
        $fileToRead = fopen($inputFile, "r");
        $fileToWrite = fopen($outputFile, "w");

        while (!feof($fileToRead)) {
            $recordTimestamp = null;
            $line = preg_replace($spaceRegex, $spaceMockCharacter, fgets($fileToRead));
            if (empty($line)) continue;

            $result = str_getcsv($line, separator: $delimiter ?? " ");
            if ($dateColumnIndex < 0) {
                foreach ($result as $index => $value) {
                    try {
                        $timestamp = empty($value) ? null : new \DateTime(str_replace($replaceDateChars["from"], $replaceDateChars["to"], $value));
                    } catch (\Exception $e) {
                        $timestamp = null;
                    }
                    if ($dateColumnIndex < 0 && $timestamp?->getTimestamp() > 0) {
                        $dateColumnIndex = $index;
                        $recordTimestamp = $timestamp;
                    }
                }
            } else {
                try {
                    $value = $result[$dateColumnIndex];
                    $recordTimestamp = empty($value) ? null : new \DateTime(str_replace($replaceDateChars["from"], $replaceDateChars["to"], $value));
                } catch (\Exception $e) {
                    $recordTimestamp = null;
                }
            }
            $pattern = str_repeat("%s{$delimiter}", count($result));
            $pattern = substr($pattern, 0, (strlen($pattern) - strlen($delimiter)));
            $timeperiod = !empty($dateFrom) | !empty($dateTo) << 1;
            $printedLine = "";
            var_dump( // DEBUG
                [
                    "dateColumnIndex" => $dateColumnIndex,
                    "recordTimestamp" => $recordTimestamp?->getTimestamp(),
                    "dateFromTimestamp" => $dateFrom?->getTimestamp(),
                    "dateToTimestamp" => $dateTo?->getTimestamp(),
                ]
            );
            switch (intval($timeperiod)) {
                case 1:
                    if ($recordTimestamp?->getTimestamp() > $dateFrom?->getTimestamp()) {
                        $result[$dateColumnIndex] = preg_replace($spaceRegexRevert, " ", $result[$dateColumnIndex]);
                        $printedLine = sprintf("$pattern\n", ...$result);
                    }
                    break;
                case 2:
                    if ($recordTimestamp?->getTimestamp() < $dateTo?->getTimestamp()) {
                        $result[$dateColumnIndex] = preg_replace($spaceRegexRevert, " ", $result[$dateColumnIndex]);
                        $printedLine = sprintf("$pattern\n", ...$result);
                    }
                case 3:
                    if (
                        $recordTimestamp?->getTimestamp() > $dateFrom?->getTimestamp() &&
                        $recordTimestamp?->getTimestamp() < $dateTo?->getTimestamp()
                    ) {
                        $result[$dateColumnIndex] = preg_replace($spaceRegexRevert, " ", $result[$dateColumnIndex]);
                        $printedLine = sprintf("$pattern\n", ...$result);
                    }
                    break;
                default:
                    $printedLine = sprintf("$pattern\n", ...$result);
                    break;
            }

            if (!empty($printedLine)) {
                if (boolval($stdOut)) {
                    printf("%s", $printedLine);
                }
                fwrite($fileToWrite, $printedLine);
            }
        }


        $response["close_$inputFile"] = fclose($fileToRead);
        $response["close_$outputFile"] = fclose($fileToWrite);
        if ($overrideFile) {
            $response["unlink_$inputFile"] = unlink($inputFile);
            if ($response["unlink_$inputFile"]) {
                $response["rename_$outputFile"] = rename($outputFile, $inputFile);
            } else {
                echo "File {$inputFile} can't be deleted and replaced by new file.";
            }
        }
        return $response;
    }

    public function analyse(array $config = [
        "type" => "statistics",
        "inputFile" => null,
        "outputFile" => null,
        "stdOut" => null,
        "dateFrom" => null,
        "dateTo" => null,
        "spaceRegex" => null,
        "spaceRegexRevert" => null,
        "spaceMockCharacter" => null,
        "delimiter" => null,
        "enclosure" => null,
        "replaceDateChars" => [
            "from" => [],
            "to" => []
        ],
        "dateColumnIndex" => -1
    ]): mixed
    {
        extract($config);
        $type = $type ?? $this->config?->analyseType ?? "statistics";
        $inputFile = $inputFile ?? $this->config?->inputFile ?? "";
        $outputFile = $outputFile ?? $this->config?->outputFile ?? "{$inputFile}_analyse.json";
        $stdOut = $stdOut ?? $this->config?->$stdOut ?? null;
        $dateFrom = $dateFrom ?? $this->config?->dateFrom ?? null;
        $dateTo = $dateTo ?? $this->config?->dateTo ?? null;
        $spaceRegex = $spaceRegex ?? $this->config?->dateFilter["spaceRegex"] ?? null;
        $spaceRegexRevert = $spaceRegexRevert ?? $this->config?->dateFilter["spaceRegexRevert"] ?? null;
        $spaceMockCharacter = $spaceMockCharacter ?? $this->config?->dateFilter["spaceMockCharacter"] ?? null;
        $delimiter = $delimiter ?? $this->config?->delimiter ?? " ";
        $enclosure = $enclosure ?? $this->config?->enclosure ?? null;
        $replaceDateChars = $replaceDateChars ?? $this->config?->dateFilter["replaceDateChars"] ?? [
            "from" => [],
            "to" => []
        ];
        $dateColumnIndex = $dateColumnIndex ?? $this->config?->dateColumnIndex ?? -1;
        $overrideFile = false; // for safety, analysed file shouldn't be overwritten
        $analytics = [];
        $response = [];
        if (empty($inputFile)) throw new InvalidArgumentException("No input file");
        if ($outputFile == $inputFile) {
            $outputFile = "{$outputFile}_analyse.json";
        }
        $fileToRead = fopen($inputFile, "r");
        $fileToWrite = fopen($outputFile, "w");

        while (!feof($fileToRead)) {
            $recordTimestamp = null;
            $line = preg_replace($spaceRegex, $spaceMockCharacter, fgets($fileToRead));
            if (empty($line)) continue;

            $result = str_getcsv($line, separator: $delimiter ?? " ");
            if ($dateColumnIndex < 0) {
                foreach ($result as $index => $value) {
                    try {
                        $timestamp = empty($value) ? null : new \DateTime(str_replace($replaceDateChars["from"], $replaceDateChars["to"], $value));
                    } catch (\Exception $e) {
                        $timestamp = null;
                    }
                    if ($dateColumnIndex < 0 && $timestamp?->getTimestamp() > 0) {
                        $dateColumnIndex = $index;
                        $recordTimestamp = $timestamp;
                    }
                }
            } else {
                try {
                    $value = $result[$dateColumnIndex];
                    $recordTimestamp = empty($value) ? null : new \DateTime(str_replace($replaceDateChars["from"], $replaceDateChars["to"], $value));
                } catch (\Exception $e) {
                    $recordTimestamp = null;
                }
            }
            // $pattern = str_repeat("%s{$delimiter}", count($result));
            // $pattern = substr($pattern, 0, (strlen($pattern) - strlen($delimiter)));
            $timeperiod = !empty($dateFrom) | !empty($dateTo) << 1;
            // $printedLine = "";
            switch ($type) {
                case "statistics":
                default:
                    switch (intval($timeperiod)) {
                        case 1:
                            if ($recordTimestamp?->getTimestamp() > $dateFrom?->getTimestamp()) {
                                $result[$dateColumnIndex] = preg_replace($spaceRegexRevert, " ", $result[$dateColumnIndex]);
                                foreach ($result as $index => $value) {
                                    $analytics[$value] = empty($analytics[$value]) ? 1 : $analytics[$value]+1;
                                }
                            }
                            break;
                        case 2:
                            if ($recordTimestamp?->getTimestamp() < $dateTo?->getTimestamp()) {
                                $result[$dateColumnIndex] = preg_replace($spaceRegexRevert, " ", $result[$dateColumnIndex]);
                                foreach ($result as $index => $value) {
                                    $analytics[$value] = empty($analytics[$value]) ? 1 : $analytics[$value]+1;
                                }
                            }
                        case 3:
                            if (
                                $recordTimestamp?->getTimestamp() > $dateFrom?->getTimestamp() &&
                                $recordTimestamp?->getTimestamp() < $dateTo?->getTimestamp()
                            ) {
                                $result[$dateColumnIndex] = preg_replace($spaceRegexRevert, " ", $result[$dateColumnIndex]);
                                foreach ($result as $index => $value) {
                                    $analytics[$value] = empty($analytics[$value]) ? 1 : $analytics[$value]+1;
                                }
                            }
                            break;
                        default:
                            $result[$dateColumnIndex] = preg_replace($spaceRegexRevert, " ", $result[$dateColumnIndex]);
                            foreach ($result as $index => $value) {
                                $analytics[$value] = empty($analytics[$value]) ? 1 : $analytics[$value]+1;
                            }
                            break;
                    }
                    break;
            }

        }

        ksort($analytics);
        $analyticsJson = json_encode($analytics, JSON_PRETTY_PRINT);
        if (boolval($stdOut)) {
            printf("%s", $analyticsJson);
        }
        $response["write_$outputFile"] = fwrite($fileToWrite, $analyticsJson);

        $response["close_$inputFile"] = fclose($fileToRead);
        $response["close_$outputFile"] = fclose($fileToWrite);
        if ($overrideFile) {
            $response["unlink_$inputFile"] = unlink($inputFile);
            if ($response["unlink_$inputFile"]) {
                $response["rename_$outputFile"] = rename($outputFile, $inputFile);
            } else {
                echo "File {$inputFile} can't be deleted and replaced by new file.";
            }
        }
        return $response;
    }
}
