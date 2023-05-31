<?php

namespace App\Console\Commands;

use App\Models\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class HashCommand extends Command
{
    protected $signature = 'avato:test {string} {--requests=}';

    protected $description = 'Command to query the hash route and store the results in the database';

    public function handle()
    {
        $string = $this->argument('string');
        $requests = $this->option('requests');

        $bar = $this->output->createProgressBar($requests);
        $bar->start();

        $response = Http::get(url('/api/hash/' . $string));

        if ($response->status() === 429) {
            $this->newLine();
            $this->error('status: '.$response->status().' Too Many Attempts waiting 1 min...');
            sleep(60);
            $response = Http::get(url('/api/hash/' . $string));
        }else{
            $data = json_decode($response->body());
            $batchId = now()->format('Y-m-d H:i:s');
            $this->storeResult($batchId,  $string, $data->key, $data->hash, $data->attempts);
            $bar->advance();
            for ($i = 2; $i <= $requests; $i++) {
                $lastHash = $data->hash;
                $response = Http::get(url('/api/hash/' . $lastHash));

                if ($response->status() === 200) {
                    $data = json_decode($response->body());
                    $this->storeResult($batchId,  $lastHash, $data->key, $data->hash, $data->attempts);
                    $bar->advance();
                }elseif ($response->status() === 429) {
                    $this->newLine();
                    $this->error('status: '.$response->status().' Too Many Attempts waiting 1 min...');
                    sleep(60);
                }else {
                    $this->newLine();
                    $this->error('status: '.$response->status().' Error');
                    break;
                }
            }
            $bar->finish();
            $this->newLine();
            $this->info('Command executed successfully.');
        }
    }

    private function storeResult($batchId, $inputString, $Key, $hash, $attempts)
    {
        Result::create([
             'batch' => $batchId,
             'input_string' => $inputString,
             'key' => $Key,
             'hash' => $hash,
             'attempts' => $attempts
         ]);
    }
}
