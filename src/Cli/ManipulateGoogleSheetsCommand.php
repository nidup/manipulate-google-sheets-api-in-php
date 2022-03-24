<?php

declare(strict_types=1);

namespace App\Cli;

use Google\Service\Sheets\BatchGetValuesByDataFilterRequest;
use Google\Service\Sheets\DataFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ManipulateGoogleSheetsCommand extends Command
{
    protected function configure()
    {
        $this->setName('nidup:google-sheets:manipulate-google-sheets')
            ->setDescription('Manipulate Google Sheets')
            ->addArgument('spreadsheet_id', InputArgument::REQUIRED, 'The spreadsheet id that can be found in the url');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Google\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // the $spreadsheetId can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYCQ-s7perMaBlaBlaBla/edit
        $spreadsheetId = $input->getArgument('spreadsheet_id');

        // configure the Google Client
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets API');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        // credentials.json is the key file we downloaded while setting up our Google Sheets API
        $path = 'data/credentials.json';
        $client->setAuthConfig($path);
        // configure the Sheets Service
        $service = new \Google_Service_Sheets($client);

        // get the spreadsheet
        if (false) {
            $spreadsheet = $service->spreadsheets->get($spreadsheetId);
            var_dump($spreadsheet);
            exit();
        }

        // get all the rows of a sheet
        if (true) {
            $range = 'Sheet1'; // here we use the name of the Sheet to get all the rows
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
            var_dump($values);
        }

        // retrieve the 10 first rows of the sheet
        if (false) {
            $range = 'Sheet1!A1:F10';
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
            var_dump($values);
        }

        // Transform Rows in Associative Array
        if (false) {
            // Fetch the rows
            $range = 'Sheet1';
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues();
            // Remove the first one that contains headers
            $headers = array_shift($rows);
            // Combine the headers with each following row
            $array = [];
            foreach ($rows as $row) {
                $array[] = array_combine($headers, $row);
            }
            var_dump($array);

            // transform to json string
            $jsonString = json_encode($array, JSON_PRETTY_PRINT);
            print($jsonString);
        }

        // Fetch a single column
        if (false) {
            $range = 'Sheet1!B1:B21'; // the title column
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
            var_dump($values);
        }


        // Fetch the rows by a filter
        // TODO: does not work
        /*
        $range = 'Sheet1';
        $filters = new BatchGetValuesByDataFilterRequest();
        $filters->setDataFilters(
            [
                [
                    "developerMetadataLookup" => [
                        "metadataValue" => "Captain Marvel"
                    ]
                ]
            ]
        );
        $response = $service->spreadsheets_values->batchGetByDataFilter($spreadsheetId, $filters);
        $values = $response->getValueRanges();
        var_dump($values);
        */

        // append a new row
        if (false) {
            $newRow = [
                '456740',
                'Hellboy',
                'https://image.tmdb.org/t/p/w500/bk8LyaMqUtaQ9hUShuvFznQYQKR.jpg',
                "Hellboy comes to England, where he must defeat Nimue, Merlin's consort and the Blood Queen. But their battle will bring about the end of the world, a fate he desperately tries to turn away.",
                '1554944400',
                'Fantasy, Action'
            ];
            $rows = [$newRow];
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Sheet1';
            $options = ['valueInputOption' => 'USER_ENTERED'];
            $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
        }

        // update a row
        if (false) {
            $updateRow = [
                '456740',
                'Hellboy Updated Row',
                'https://image.tmdb.org/t/p/w500/bk8LyaMqUtaQ9hUShuvFznQYQKR.jpg',
                "Hellboy comes to England, where he must defeat Nimue, Merlin's consort and the Blood Queen. But their battle will bring about the end of the world, a fate he desperately tries to turn away.",
                '1554944400',
                'Fantasy, Action'
            ];
            $rows = [$updateRow];
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Sheet1!A2'; // where the replacement will start, here, first column and second line
            $options = ['valueInputOption' => 'USER_ENTERED'];
            $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
        }

        if (false) {
            // delete some rows
            $range = 'Sheet1!A23:F24'; // the range to clear, the 23th and 24th lines
            $clear = new \Google_Service_Sheets_ClearValuesRequest();
            $service->spreadsheets_values->clear($spreadsheetId, $range, $clear);
        }

        return 0;
    }
}
