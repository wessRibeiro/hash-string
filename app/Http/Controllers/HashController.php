<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResultCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Result;

class HashController extends Controller
{
    private bool $startWith0000 = false;
    private string $key = '';
    private int $attempts = 0;
    private string $hash = '';

    public function show(String $string) {
        return response()->json($this->generateHash($string));
    }

    public function index(Request $request) {
        $perPage = 10;
        $attempts = $request->input('attempts', null);

        $results = Result::query()
            ->when($attempts, function ($query) use ($attempts) {
                $query->where('attempts', '<', $attempts);
            })
            ->orderBy('batch', 'desc')
            ->paginate($perPage);

        return new ResultCollection($results);
    }

    private function generateHash(string $string): array{
        while (!$this->startWith0000):
            $this->key = Str::random(8);
            $this->hash = Str::of($this->key.$string)->pipe('md5');
            if(Str::startsWith($this->hash, '0000')) {
                $this->startWith0000 = true;
            }
            $this->attempts++;
        endwhile;

        return [
            'string' => $string,
            'key' => $this->key,
            'hash' => $this->hash,
            'attempts' => $this->attempts
        ];
    }
}
