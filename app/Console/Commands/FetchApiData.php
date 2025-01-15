<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchApiData extends Command
{
    protected $signature = 'fetch:api-data {entity} {--accountId=} {--dateFrom=} {--dateTo=} {--key=} {--limit=500}';

    protected $description = 'Fetch data from API and store it in database';

    private const HOST = '89.108.115.241';
    private const PORT = '6969';
    private const API_KEY = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    private const MODEL_MAP = [
        'stocks' => Stock::class,
        'incomes' => Income::class,
        'sales' => Sale::class,
        'orders' => Order::class
    ];

    public function handle(): void
    {
        $entity = $this->argument('entity');
        $accountId = $this->option('accountId') ?? '5';
        $dateFrom = $this->option('dateFrom') ?? Carbon::now('Europe/Moscow')->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?? Carbon::now('Europe/Moscow')->format('Y-m-d');
        $apiKey = $this->option('key') ?? 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';
        $limit = $this->option('limit');

        $this->info("Fetching data for entity: $entity");
        $this->info("Parameters: dateFrom=$dateFrom, dateTo=$dateTo, key=$apiKey, limit=$limit");

        Log::info("Start fetching data", [
            'entity' => $entity,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'key' => $apiKey,
            'limit' => $limit
        ]);

        try {
            $account = Account::query()->findOrFail($accountId);
        } catch (ModelNotFoundException $e) {
            $this->error('Account not found' . $e->getMessage());
            Log::error('Account not found', ['error' => $e->getMessage()]);
            return;
        }
        try {
            $this->fetchAndStoreData($account, $entity, $dateFrom, $dateTo, $apiKey, $limit);
        } catch (RequestException $e) {
            $this->error('Request exception: ' . $e->getMessage());
            Log::error('Request exception', ['error' => $e->getMessage()]);
        }
    }

    private function fetchAndStoreData(Account $account, string $entity, string $dateFrom, string $dateTo, string $key, int $limit): void
    {
        foreach (self::MODEL_MAP as $modelName => $model) {
            $params = [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => 1,
                'key' => self::API_KEY,
                'limit' => $limit
            ];

            if ($modelName === $entity) {
                if($modelName === 'stocks'){
                    $params['dateTo'] = $params['dateFrom'] = Carbon::now('Europe/Moscow')->format('Y-m-d');
                }
                
                $this->info("API request to $modelName endpoint with parameters: " . json_encode($params));
                Log::info("API request to $modelName endpoint with parameters", $params);

                $response = Http::get(self::HOST . ':' . self::PORT . "/api/$modelName", $params);

                if (!$response) {
                    continue;
                }
                $totalPages = $response->json('meta')['last_page'];

                for ($i = $params['page']; $i <= $totalPages; $i++) {
                    $params['page'] = $i;
                    $response = $this->makeApiRequest(self::HOST . ':' . self::PORT . "/api/$modelName", $params);

                    if (!$response) {
                        continue;
                    }
                    $data = $response->json('data');
                    foreach ($data as $datum) {
                        $account->$modelName()->updateOrCreate($datum);
                    }
                    $this->info(ucfirst("$modelName data fetched successfully."));
                    Log::info("$modelName data fetched successfully.");
                }
            }
        }
    }


    private function makeApiRequest(string $url, array $params): ?Response
    {
        try {
            return Http::retry(5, 100, function ($exception) {
                return $exception instanceof RequestException && $exception->getCode() === 429;
            })->get($url, $params);
        } catch (RequestException $e) {
            $this->error('Request exception: ' . $e->getMessage());
            Log::error('Request exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
