<?php

namespace LogCleaner\LogProcessor;

use LogCleaner\LogConfig\LogConfigInterface;
use InvalidArgumentException;

abstract class AbstractFileLogProcessor implements LogProcessorInterface
{

    public function __construct(protected LogConfigInterface $config)
    {
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
        $outputFile = $outputFile ?? $this->config?->outputFile ?? "tmp_{$inputFile}";
        $stdOut = $stdOut ?? $this->config?->$stdOut ?? null;
        $dateFrom = $dateFrom ?? $this->config?->dateFrom ?? null;
        $dateTo = $dateTo ?? $this->config?->dateTo ?? null;
        $spaceRegex = $spaceRegex ?? $this->config?->spaceRegex ?? null;
        $spaceRegexRevert = $spaceRegexRevert ?? $this->config?->spaceRegexRevert ?? null;
        $spaceMockCharacter = $spaceMockCharacter ?? $this->config?->spaceMockCharacter ?? null;
        $delimiter = $delimiter ?? $this->config?->delimiter ?? " ";
        $enclosure = $enclosure ?? $this->config?->enclosure ?? null;
        $replaceDateChars = $replaceDateChars ?? $this->config?->replaceDateChars ?? [
            "from" => [],
            "to" => []
        ];
        $dateColumnIndex = $dateColumnIndex ?? $this->config?->dateColumnIndex ?? -1;
        $overrideFile = false;
        if (empty($inputFile)) throw new InvalidArgumentException("No input file");
        if ($outputFile == $inputFile) {
            $overrideFile = true;
            $outputFile = "tmp_{$outputFile}";
        }
        $fileToRead = fopen($inputFile, "r");
        $fileToWrite = fopen($inputFile, "w");

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

            switch (intval($timeperiod)) {
                case 1:
                    if ($recordTimestamp?->getTimestamp() > $dateFrom?->getTimestamp()) {
                        $result[$dateColumnIndex] = preg_replace("/(?<=[0-9])_(?=[\+-][0-9]{4}\])/", " ", $result[$dateColumnIndex]);
                        $printedLine = sprintf("$pattern\n", ...$result);
                    }
                    break;
                case 2:
                    if ($recordTimestamp?->getTimestamp() < $dateTo?->getTimestamp()) {
                        $result[$dateColumnIndex] = preg_replace("/(?<=[0-9])_(?=[\+-][0-9]{4}\])/", " ", $result[$dateColumnIndex]);
                        $printedLine = sprintf("$pattern\n", ...$result);
                    }
                case 3:
                    if (
                        $recordTimestamp?->getTimestamp() > $dateFrom?->getTimestamp() &&
                        $recordTimestamp?->getTimestamp() < $dateTo?->getTimestamp()
                    ) {
                        $result[$dateColumnIndex] = preg_replace("/(?<=[0-9])_(?=[\+-][0-9]{4}\])/", " ", $result[$dateColumnIndex]);
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

        fclose($fileToRead);
        fclose($fileToWrite);
        if ($overrideFile) {
            if (unlink($inputFile)) {
                rename($outputFile, $inputFile);
            } else {
                echo "File {$inputFile} can't be deleted and replaced by new file.";
            }
        }
    }
}
