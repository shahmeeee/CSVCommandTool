<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class GenerateCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateCSV {--string=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CSV from string';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->validateString()) {
            return;
        };

        $string = $this->option('string');

        $this->capitalize($string, 'even');
        $this->capitalize($string, 'odd');

        $this->exportToCSV($string);
        $this->info('CSV created!');
    }

    /**
     * @return bool
     */
    protected function validateString(): bool
    {
        if ($this->option('string') == null) {
            $this->info('example : php artisan generateCSV --string="lord of the rings"');
            return true;
        }

        if (preg_match('/[^a-zA-Z]/', $this->option('string'))) {
            $this->info('text should only contain alphabets letters');
            return true;
        }
        return false;
    }

    protected function exportToCSV($string)
    {
        $stringSplit = str_split(strtolower($string));
        $fileContent = implode(',', $stringSplit);
        $csv_filename = 'generated_on' . "_" . date("Y-m-d_H-i", time()) . ".csv";
        $fd = fopen($csv_filename, "w");
        fputs($fd, $fileContent);
        fclose($fd);
    }

    public function capitalize($string, $parity) : void
    {
        $return = "";
        $postion = $parity == 'even' ? 0 : 1;

        $stringArray = explode(" ", strtolower($string));
        foreach ($stringArray as $word) {
            foreach (str_split($word) as $k => $v) {
                if (($k + 1) % 2 != $postion && ctype_alpha($v)) {
                    $return .= mb_strtoupper($v);
                } else {
                    $return .= $v;
                }
            }
            $return .= " ";
        }

        $this->info(trim($return));

    }
}
