#!/usr/bin/env php
<?php

/**
 * gRPC Client Debug Script
 * 
 * Usage:
 *   php grpc_debug.php                        # run all tests
 *   php grpc_debug.php wallet                 # test wallet service only
 *   php grpc_debug.php transaction            # test transaction service only
 *   php grpc_debug.php investment             # test investment service only
 *   php grpc_debug.php wallet listWalletTypes # test specific method
 * 
 * Requirements:
 *   - PHP gRPC extension: pecl install grpc
 *   - Protobuf extension: pecl install protobuf
 *   - Generated PHP files from refina-protobuf (autoloaded via composer or manual require)
 */

// ── Autoload ──────────────────────────────────────────────────────────────────

// Coba composer autoload dulu (jika ada vendor/)
$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
];
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        break;
    }
}

// Manual require generated PHP files jika tidak pakai composer
// Uncomment dan sesuaikan path jika perlu:
// $protoBase = __DIR__ . '/../refina-proto';
// foreach (['wallet', 'transaction', 'investment', 'dashboard'] as $dir) {
//     foreach (glob("$protoBase/$dir/*.php") as $file) {
//         require_once $file;
//     }
// }

// ── Config ────────────────────────────────────────────────────────────────────

$config = [
    'wallet_address'      => getenv('GRPC_WALLET_ADDRESS')      ?: 'localhost:10001',
    'transaction_address' => getenv('GRPC_TRANSACTION_ADDRESS') ?: 'localhost:10002',
    'investment_address'  => getenv('GRPC_INVESTMENT_ADDRESS')  ?: 'localhost:10003',
];

// ── CLI args ──────────────────────────────────────────────────────────────────

$filterService = $argv[1] ?? null; // e.g., 'wallet', 'transaction', 'investment'
$filterMethod  = $argv[2] ?? null; // e.g., 'listWalletTypes'

// ── Helpers ───────────────────────────────────────────────────────────────────

function printHeader(string $title): void
{
    $line = str_repeat('─', 60);
    echo "\n\033[1;34m┌{$line}┐\033[0m\n";
    echo "\033[1;34m│\033[0m \033[1;33m{$title}\033[0m\n";
    echo "\033[1;34m└{$line}┘\033[0m\n";
}

function printOk(string $method, mixed $data): void
{
    echo "\033[1;32m✅ {$method}\033[0m\n";
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}

function printFail(string $method, string $error): void
{
    echo "\033[1;31m❌ {$method}\033[0m\n";
    echo "\033[0;31m   Error: {$error}\033[0m\n";
}

function printSkip(string $method): void
{
    echo "\033[0;90m⏭  {$method} — skipped\033[0m\n";
}

function grpcCall(callable $fn, string $method): mixed
{
    [$response, $status] = $fn();

    if ($status->code !== \Grpc\STATUS_OK) {
        throw new \RuntimeException(
            "code={$status->code} details={$status->details}"
        );
    }

    return $response;
}

function shouldRun(?string $filterService, ?string $filterMethod, string $service, string $method): bool
{
    if ($filterService !== null && strtolower($filterService) !== strtolower($service)) {
        return false;
    }
    if ($filterMethod !== null && strtolower($filterMethod) !== strtolower($method)) {
        return false;
    }
    return true;
}

// ── Preflight checks ──────────────────────────────────────────────────────────

printHeader('Preflight Checks');

$checks = [
    'grpc extension'      => extension_loaded('grpc'),
    'protobuf extension'  => extension_loaded('protobuf'),
    'Grpc\ChannelCredentials class' => class_exists(\Grpc\ChannelCredentials::class),
];

$allOk = true;
foreach ($checks as $label => $ok) {
    if ($ok) {
        echo "\033[1;32m✅\033[0m {$label}\n";
    } else {
        echo "\033[1;31m❌\033[0m {$label} — NOT FOUND\n";
        $allOk = false;
    }
}

$classChecks = [
    'Wallet\WalletServiceClient'           => class_exists(\Wallet\WalletServiceClient::class),
    'Wallet\ListWalletTypesRequest'        => class_exists(\Wallet\ListWalletTypesRequest::class),
    'Wallet\WalletTypeID'                  => class_exists(\Wallet\WalletTypeID::class),
    'Transaction\TransactionServiceClient' => class_exists(\Transaction\TransactionServiceClient::class),
    'Transaction\ListCategoriesRequest'    => class_exists(\Transaction\ListCategoriesRequest::class),
    'Transaction\CategoryID'               => class_exists(\Transaction\CategoryID::class),
    'Investment\InvestmentServiceClient'   => class_exists(\Investment\InvestmentServiceClient::class),
    'Investment\ListAssetCodesRequest'     => class_exists(\Investment\ListAssetCodesRequest::class),
    'Investment\AssetCodeID'               => class_exists(\Investment\AssetCodeID::class),
];

foreach ($classChecks as $class => $exists) {
    if ($exists) {
        echo "\033[1;32m✅\033[0m {$class}\n";
    } else {
        echo "\033[0;33m⚠️ \033[0m {$class} — not found (autoload belum terpasang?)\n";
        $allOk = false;
    }
}

if (!$allOk) {
    echo "\n\033[1;33m⚠️  Ada dependency yang belum terpasang. Script tetap jalan, tapi beberapa test mungkin gagal.\033[0m\n";
}

echo "\n\033[0;90mConfig:\033[0m\n";
foreach ($config as $k => $v) {
    echo "   \033[0;36m{$k}\033[0m = {$v}\n";
}

// ── Wallet Service ─────────────────────────────────────────────────────────────

if (shouldRun($filterService, null, 'wallet', '')) {
    printHeader('Wallet Service @ ' . $config['wallet_address']);

    $walletClient = new \Wallet\WalletServiceClient(
        $config['wallet_address'],
        ['credentials' => \Grpc\ChannelCredentials::createInsecure()],
    );

    // listWalletTypes
    $method = 'listWalletTypes';
    if (shouldRun($filterService, $filterMethod, 'wallet', $method)) {
        try {
            $req = new \Wallet\ListWalletTypesRequest();
            $req->setPage(1);
            $req->setPageSize(5);
            $req->setSortBy('created_at');
            $req->setSortOrder('desc');
            $req->setSearch('');

            $resp = grpcCall(
                fn() => $walletClient->ListWalletTypes($req)->wait(),
                'wallet.WalletService/ListWalletTypes'
            );

            $items = [];
            foreach ($resp->getWalletTypes() as $wt) {
                $items[] = [
                    'id'   => $wt->getId(),
                    'name' => $wt->getName(),
                    'type' => $wt->getType(),
                ];
            }
            printOk($method, [
                'total'       => $resp->getTotal(),
                'page'        => $resp->getPage(),
                'pageSize'    => $resp->getPageSize(),
                'totalPages'  => $resp->getTotalPages(),
                'walletTypes' => $items,
            ]);

            // Ambil ID pertama untuk test detail
            $firstWalletTypeId = $items[0]['id'] ?? null;

        } catch (\Throwable $e) {
            printFail($method, $e->getMessage());
            $firstWalletTypeId = null;
        }
    } else {
        printSkip($method);
        $firstWalletTypeId = null;
    }

    // getWalletTypeDetail
    $method = 'getWalletTypeDetail';
    if (shouldRun($filterService, $filterMethod, 'wallet', $method)) {
        $testId = $firstWalletTypeId ?? getenv('DEBUG_WALLET_TYPE_ID') ?: null;
        if ($testId) {
            try {
                $req = new \Wallet\WalletTypeID();
                $req->setId($testId);

                $wt = grpcCall(
                    fn() => $walletClient->GetWalletTypeDetail($req)->wait(),
                    'wallet.WalletService/GetWalletTypeDetail'
                );

                printOk($method, [
                    'id'          => $wt->getId(),
                    'name'        => $wt->getName(),
                    'type'        => $wt->getType(),
                    'description' => $wt->getDescription(),
                    'createdAt'   => $wt->getCreatedAt(),
                    'updatedAt'   => $wt->getUpdatedAt(),
                ]);
            } catch (\Throwable $e) {
                printFail($method, $e->getMessage());
            }
        } else {
            echo "\033[0;33m⚠️  {$method} — no ID available (set DEBUG_WALLET_TYPE_ID env or run listWalletTypes first)\033[0m\n";
        }
    } else {
        printSkip($method);
    }

    $walletClient->close();
}

// ── Transaction Service ────────────────────────────────────────────────────────

if (shouldRun($filterService, null, 'transaction', '')) {
    printHeader('Transaction Service @ ' . $config['transaction_address']);

    $transactionClient = new \Transaction\TransactionServiceClient(
        $config['transaction_address'],
        ['credentials' => \Grpc\ChannelCredentials::createInsecure()],
    );

    // listCategories
    $method = 'listCategories';
    if (shouldRun($filterService, $filterMethod, 'transaction', $method)) {
        try {
            $req = new \Transaction\ListCategoriesRequest();
            $req->setPage(1);
            $req->setPageSize(5);
            $req->setSortBy('created_at');
            $req->setSortOrder('desc');
            $req->setSearch('');
            $req->setType('');

            $resp = grpcCall(
                fn() => $transactionClient->ListCategories($req)->wait(),
                'transaction.TransactionService/ListCategories'
            );

            $items = [];
            foreach ($resp->getCategories() as $cat) {
                $items[] = [
                    'id'         => $cat->getId(),
                    'name'       => $cat->getName(),
                    'type'       => $cat->getType(),
                    'parentId'   => $cat->getParentId(),
                    'parentName' => $cat->getParentName(),
                ];
            }
            printOk($method, [
                'total'      => $resp->getTotal(),
                'page'       => $resp->getPage(),
                'pageSize'   => $resp->getPageSize(),
                'totalPages' => $resp->getTotalPages(),
                'categories' => $items,
            ]);

            $firstCategoryId = $items[0]['id'] ?? null;

        } catch (\Throwable $e) {
            printFail($method, $e->getMessage());
            $firstCategoryId = null;
        }
    } else {
        printSkip($method);
        $firstCategoryId = null;
    }

    // getCategoryDetail
    $method = 'getCategoryDetail';
    if (shouldRun($filterService, $filterMethod, 'transaction', $method)) {
        $testId = $firstCategoryId ?? getenv('DEBUG_CATEGORY_ID') ?: null;
        if ($testId) {
            try {
                $req = new \Transaction\CategoryID();
                $req->setId($testId);

                $cat = grpcCall(
                    fn() => $transactionClient->GetCategoryDetail($req)->wait(),
                    'transaction.TransactionService/GetCategoryDetail'
                );

                printOk($method, [
                    'id'         => $cat->getId(),
                    'name'       => $cat->getName(),
                    'type'       => $cat->getType(),
                    'parentId'   => $cat->getParentId(),
                    'parentName' => $cat->getParentName(),
                    'createdAt'  => $cat->getCreatedAt(),
                ]);
            } catch (\Throwable $e) {
                printFail($method, $e->getMessage());
            }
        } else {
            echo "\033[0;33m⚠️  {$method} — no ID available (set DEBUG_CATEGORY_ID env or run listCategories first)\033[0m\n";
        }
    } else {
        printSkip($method);
    }

    $transactionClient->close();
}

// ── Investment Service ─────────────────────────────────────────────────────────

if (shouldRun($filterService, null, 'investment', '')) {
    printHeader('Investment Service @ ' . $config['investment_address']);

    $investmentClient = new \Investment\InvestmentServiceClient(
        $config['investment_address'],
        ['credentials' => \Grpc\ChannelCredentials::createInsecure()],
    );

    // listAssetCodes
    $method = 'listAssetCodes';
    if (shouldRun($filterService, $filterMethod, 'investment', $method)) {
        try {
            $req = new \Investment\ListAssetCodesRequest();
            $req->setPage(1);
            $req->setPageSize(5);
            $req->setSortBy('code');
            $req->setSortOrder('asc');
            $req->setSearch('');

            $resp = grpcCall(
                fn() => $investmentClient->ListAssetCodes($req)->wait(),
                'investment.InvestmentService/ListAssetCodes'
            );

            $items = [];
            foreach ($resp->getAssetCodes() as $ac) {
                $items[] = [
                    'code'  => $ac->getCode(),
                    'name'  => $ac->getName(),
                    'unit'  => $ac->getUnit(),
                    'toIDR' => $ac->getToidr(),
                ];
            }
            printOk($method, [
                'total'      => $resp->getTotal(),
                'page'       => $resp->getPage(),
                'pageSize'   => $resp->getPageSize(),
                'totalPages' => $resp->getTotalPages(),
                'assetCodes' => $items,
            ]);

            $firstAssetCode = $items[0]['code'] ?? null;

        } catch (\Throwable $e) {
            printFail($method, $e->getMessage());
            $firstAssetCode = null;
        }
    } else {
        printSkip($method);
        $firstAssetCode = null;
    }

    // getAssetCodeDetail
    $method = 'getAssetCodeDetail';
    if (shouldRun($filterService, $filterMethod, 'investment', $method)) {
        $testCode = $firstAssetCode ?? getenv('DEBUG_ASSET_CODE') ?: null;
        if ($testCode) {
            try {
                $req = new \Investment\AssetCodeID();
                $req->setCode($testCode);

                $ac = grpcCall(
                    fn() => $investmentClient->GetAssetCodeDetail($req)->wait(),
                    'investment.InvestmentService/GetAssetCodeDetail'
                );

                printOk($method, [
                    'code'      => $ac->getCode(),
                    'name'      => $ac->getName(),
                    'unit'      => $ac->getUnit(),
                    'toUSD'     => $ac->getTousd(),
                    'toEUR'     => $ac->getToeur(),
                    'toIDR'     => $ac->getToidr(),
                    'createdAt' => $ac->getCreatedAt(),
                    'updatedAt' => $ac->getUpdatedAt(),
                ]);
            } catch (\Throwable $e) {
                printFail($method, $e->getMessage());
            }
        } else {
            echo "\033[0;33m⚠️  {$method} — no code available (set DEBUG_ASSET_CODE env or run listAssetCodes first)\033[0m\n";
        }
    } else {
        printSkip($method);
    }

    $investmentClient->close();
}

// ── Summary ────────────────────────────────────────────────────────────────────

printHeader('Done');
echo "Tips:\n";
echo "  Set env untuk override address:    GRPC_WALLET_ADDRESS=host:port php grpc_debug.php\n";
echo "  Set env untuk specific ID test:    DEBUG_WALLET_TYPE_ID=xxx php grpc_debug.php\n";
echo "                                     DEBUG_CATEGORY_ID=xxx php grpc_debug.php\n";
echo "                                     DEBUG_ASSET_CODE=BTC php grpc_debug.php\n";
echo "  Filter service:                    php grpc_debug.php wallet\n";
echo "  Filter method:                     php grpc_debug.php wallet listWalletTypes\n";
echo "\n";
