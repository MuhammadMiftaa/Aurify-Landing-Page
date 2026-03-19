<?php
namespace App\Services\Grpc;

use Illuminate\Support\Facades\Log;

// Generated classes dari muhammadmiftaa/refina-protobuf
use Investment\InvestmentServiceClient;
use Investment\ListAssetCodesRequest;
use Investment\AssetCodeID;
use Transaction\CategoryID;
use Wallet\WalletServiceClient;
use Wallet\ListWalletTypesRequest;
use Wallet\WalletTypeID;

use Transaction\TransactionServiceClient;
use Transaction\ListCategoriesRequest;

class GrpcClient
{
    // ── Client instances (lazy-initialized) ───────────────────────────────────

    private ?InvestmentServiceClient  $investmentClient  = null;
    private ?WalletServiceClient      $walletClient      = null;
    private ?TransactionServiceClient $transactionClient = null;

    // ── Constructor ───────────────────────────────────────────────────────────

    public function __construct(
        private readonly string $walletAddress      = '',
        private readonly string $transactionAddress = '',
        private readonly string $investmentAddress  = '',
    ) {}

    public static function make(): static
    {
        return new static(
            walletAddress:      config('grpc.wallet_address',      'localhost:10001'),
            transactionAddress: config('grpc.transaction_address', 'localhost:10002'),
            investmentAddress:  config('grpc.investment_address',  'localhost:10003'),
        );
    }

    // ── Client factories (lazy init) ──────────────────────────────────────────

    private function investmentClient(): InvestmentServiceClient
    {
        return $this->investmentClient ??= new InvestmentServiceClient(
            $this->investmentAddress,
            ['credentials' => \Grpc\ChannelCredentials::createInsecure()],
        );
    }

    private function walletClient(): WalletServiceClient
    {
        return $this->walletClient ??= new WalletServiceClient(
            $this->walletAddress,
            ['credentials' => \Grpc\ChannelCredentials::createInsecure()],
        );
    }

    private function transactionClient(): TransactionServiceClient
    {
        return $this->transactionClient ??= new TransactionServiceClient(
            $this->transactionAddress,
            ['credentials' => \Grpc\ChannelCredentials::createInsecure()],
        );
    }

    // ── Internal: call helper ─────────────────────────────────────────────────

    /**
     * Jalankan unary gRPC call dan throw RuntimeException jika status bukan OK.
     * Menggantikan shell_exec + grpcurl — tidak ada subprocess lagi.
     */
    private function call(callable $grpcCall, string $method): mixed
    {
        [$response, $status] = $grpcCall();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new \RuntimeException(
                "gRPC [{$method}] failed — code={$status->code} details={$status->details}"
            );
        }

        return $response;
    }

    // ── Wallet Types ──────────────────────────────────────────────────────────

    public function listWalletTypes(
        int    $page      = 1,
        int    $pageSize  = 10,
        string $sortBy    = 'created_at',
        string $sortOrder = 'desc',
        string $search    = '',
    ): array {
        try {
            $req = new ListWalletTypesRequest();
            $req->setPage($page);
            $req->setPageSize($pageSize);
            $req->setSortBy($sortBy);
            $req->setSortOrder($sortOrder);
            $req->setSearch($search);

            $resp = $this->call(
                fn () => $this->walletClient()->ListWalletTypes($req)->wait(),
                'wallet.WalletService/ListWalletTypes',
            );

            $walletTypes = [];
            foreach ($resp->getWalletTypes() as $wt) {  // fix: getWalletTypesList() → getWalletTypes()
                $walletTypes[] = [
                    'id'          => $wt->getId(),
                    'name'        => $wt->getName(),
                    'type'        => $wt->getType(),
                    'description' => $wt->getDescription(),
                    'createdAt'   => $wt->getCreatedAt(),
                    'updatedAt'   => $wt->getUpdatedAt(),
                ];
            }

            Log::info('grpc_list_wallet_types_success', [
                'service' => 'grpc_client',
                'total'   => $resp->getTotal(),
            ]);

            return [
                'walletTypes' => $walletTypes,
                'total'       => $resp->getTotal(),
                'page'        => $resp->getPage(),
                'pageSize'    => $resp->getPageSize(),
                'totalPages'  => $resp->getTotalPages(),
            ];
        } catch (\Exception $e) {
            Log::error('grpc_list_wallet_types_failed', [
                'service' => 'grpc_client',
                'error'   => $e->getMessage(),
            ]);

            return [
                'walletTypes' => [],
                'total'       => 0,
                'page'        => $page,
                'pageSize'    => $pageSize,
                'totalPages'  => 0,
            ];
        }
    }

    public function getWalletTypeDetail(string $id): ?array
    {
        try {
            $req = new WalletTypeID();
            $req->setId($id);

            $wt = $this->call(
                fn () => $this->walletClient()->GetWalletTypeDetail($req)->wait(),
                'wallet.WalletService/GetWalletTypeDetail',
            );

            return [
                'id'          => $wt->getId(),
                'name'        => $wt->getName(),
                'type'        => $wt->getType(),
                'description' => $wt->getDescription(),
                'createdAt'   => $wt->getCreatedAt(),
                'updatedAt'   => $wt->getUpdatedAt(),
            ];
        } catch (\Exception $e) {
            Log::error('grpc_get_wallet_type_detail_failed', [
                'service' => 'grpc_client',
                'id'      => $id,
                'error'   => $e->getMessage(),
            ]);

            return null;
        }
    }

    // ── Transaction Categories ────────────────────────────────────────────────

    public function listCategories(
        int    $page      = 1,
        int    $pageSize  = 10,
        string $sortBy    = 'created_at',
        string $sortOrder = 'desc',
        string $search    = '',
        string $type      = '',
    ): array {
        try {
            $req = new ListCategoriesRequest();
            $req->setPage($page);
            $req->setPageSize($pageSize);
            $req->setSortBy($sortBy);
            $req->setSortOrder($sortOrder);
            $req->setSearch($search);
            $req->setType($type);

            $resp = $this->call(
                fn () => $this->transactionClient()->ListCategories($req)->wait(),
                'transaction.TransactionService/ListCategories',
            );

            $categories = [];
            foreach ($resp->getCategories() as $cat) {  // fix: getCategoriesList() → getCategories()
                $categories[] = [
                    'id'         => $cat->getId(),
                    'name'       => $cat->getName(),
                    'type'       => $cat->getType(),
                    'parentId'   => $cat->getParentId(),
                    'parentName' => $cat->getParentName(),
                    'createdAt'  => $cat->getCreatedAt(),
                ];
            }

            Log::info('grpc_list_categories_success', [
                'service' => 'grpc_client',
                'total'   => $resp->getTotal(),
            ]);

            return [
                'categories' => $categories,
                'total'      => $resp->getTotal(),
                'page'       => $resp->getPage(),
                'pageSize'   => $resp->getPageSize(),
                'totalPages' => $resp->getTotalPages(),
            ];
        } catch (\Exception $e) {
            Log::error('grpc_list_categories_failed', [
                'service' => 'grpc_client',
                'error'   => $e->getMessage(),
            ]);

            return [
                'categories' => [],
                'total'      => 0,
                'page'       => $page,
                'pageSize'   => $pageSize,
                'totalPages' => 0,
            ];
        }
    }

    public function getCategoryDetail(string $id): ?array
    {
        try {
            $req = new CategoryID();
            $req->setId($id);

            $cat = $this->call(
                fn () => $this->transactionClient()->GetCategoryDetail($req)->wait(),
                'transaction.TransactionService/GetCategoryDetail',
            );

            return [
                'id'         => $cat->getId(),
                'name'       => $cat->getName(),
                'type'       => $cat->getType(),
                'parentId'   => $cat->getParentId(),
                'parentName' => $cat->getParentName(),
                'createdAt'  => $cat->getCreatedAt(),
            ];
        } catch (\Exception $e) {
            Log::error('grpc_get_category_detail_failed', [
                'service' => 'grpc_client',
                'id'      => $id,
                'error'   => $e->getMessage(),
            ]);

            return null;
        }
    }

    // ── Asset Codes ───────────────────────────────────────────────────────────

    public function listAssetCodes(
        int    $page      = 1,
        int    $pageSize  = 10,
        string $sortBy    = 'code',
        string $sortOrder = 'asc',
        string $search    = '',
    ): array {
        try {
            $req = new ListAssetCodesRequest();
            $req->setPage($page);
            $req->setPageSize($pageSize);
            $req->setSortBy($sortBy);
            $req->setSortOrder($sortOrder);
            $req->setSearch($search);

            $resp = $this->call(
                fn () => $this->investmentClient()->ListAssetCodes($req)->wait(),
                'investment.InvestmentService/ListAssetCodes',
            );

            $assetCodes = [];
            foreach ($resp->getAssetCodes() as $ac) {  // fix: getAssetCodesList() → getAssetCodes()
                $assetCodes[] = [
                    'code'      => $ac->getCode(),
                    'name'      => $ac->getName(),
                    'unit'      => $ac->getUnit(),
                    'toUSD'     => $ac->getTousd(),
                    'toEUR'     => $ac->getToeur(),
                    'toIDR'     => $ac->getToidr(),
                    'createdAt' => $ac->getCreatedAt(),
                    'updatedAt' => $ac->getUpdatedAt(),
                ];
            }

            Log::info('grpc_list_asset_codes_success', [
                'service' => 'grpc_client',
                'total'   => $resp->getTotal(),
            ]);

            return [
                'assetCodes' => $assetCodes,
                'total'      => $resp->getTotal(),
                'page'       => $resp->getPage(),
                'pageSize'   => $resp->getPageSize(),
                'totalPages' => $resp->getTotalPages(),
            ];
        } catch (\Exception $e) {
            Log::error('grpc_list_asset_codes_failed', [
                'service' => 'grpc_client',
                'error'   => $e->getMessage(),
            ]);

            return [
                'assetCodes' => [],
                'total'      => 0,
                'page'       => $page,
                'pageSize'   => $pageSize,
                'totalPages' => 0,
            ];
        }
    }

    public function getAssetCodeDetail(string $code): ?array
    {
        try {
            $req = new AssetCodeID();
            $req->setCode($code);

            $ac = $this->call(
                fn () => $this->investmentClient()->GetAssetCodeDetail($req)->wait(),
                'investment.InvestmentService/GetAssetCodeDetail',
            );

            return [
                'code'      => $ac->getCode(),
                'name'      => $ac->getName(),
                'unit'      => $ac->getUnit(),
                'toUSD'     => $ac->getTousd(),
                'toEUR'     => $ac->getToeur(),
                'toIDR'     => $ac->getToidr(),
                'createdAt' => $ac->getCreatedAt(),
                'updatedAt' => $ac->getUpdatedAt(),
            ];
        } catch (\Exception $e) {
            Log::error('grpc_get_asset_code_detail_failed', [
                'service' => 'grpc_client',
                'code'    => $code,
                'error'   => $e->getMessage(),
            ]);

            return null;
        }
    }

    // ── Destructor: close open channels ───────────────────────────────────────

    public function __destruct()
    {
        $this->investmentClient?->close();
        $this->walletClient?->close();
        $this->transactionClient?->close();
    }
}