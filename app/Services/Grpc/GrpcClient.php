<?php

namespace App\Services\Grpc;

use Illuminate\Support\Facades\Log;

class GrpcClient
{
    private string $walletAddress;
    private string $transactionAddress;
    private string $investmentAddress;

    public function __construct()
    {
        $this->walletAddress      = config('rabbitmq.grpc.wallet_address');
        $this->transactionAddress = config('rabbitmq.grpc.transaction_address');
        $this->investmentAddress  = config('rabbitmq.grpc.investment_address');
    }

    // ── Wallet Types ──

    public function listWalletTypes(int $page = 1, int $pageSize = 10, string $sortBy = 'created_at', string $sortOrder = 'desc', string $search = ''): array
    {
        try {
            $requestData = json_encode([
                'page'       => $page,
                'page_size'  => $pageSize,
                'sort_by'    => $sortBy,
                'sort_order' => $sortOrder,
                'search'     => $search,
            ]);

            $result = $this->callGrpc(
                $this->walletAddress,
                'wallet.WalletService/ListWalletTypes',
                $requestData,
            );

            Log::info('grpc_list_wallet_types_success', [
                'service' => 'grpc_client',
                'total'   => $result['total'] ?? 0,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('grpc_list_wallet_types_failed', [
                'service' => 'grpc_client',
                'error'   => $e->getMessage(),
            ]);
            return ['wallet_types' => [], 'total' => 0, 'page' => $page, 'page_size' => $pageSize, 'total_pages' => 0];
        }
    }

    public function getWalletTypeDetail(string $id): ?array
    {
        try {
            $result = $this->callGrpc(
                $this->walletAddress,
                'wallet.WalletService/GetWalletTypeDetail',
                json_encode(['id' => $id]),
            );

            Log::info('grpc_get_wallet_type_detail_success', [
                'service' => 'grpc_client',
                'id'      => $id,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('grpc_get_wallet_type_detail_failed', [
                'service' => 'grpc_client',
                'id'      => $id,
                'error'   => $e->getMessage(),
            ]);
            return null;
        }
    }

    // ── Transaction Categories ──

    public function listCategories(int $page = 1, int $pageSize = 10, string $sortBy = 'created_at', string $sortOrder = 'desc', string $search = '', string $type = ''): array
    {
        try {
            $result = $this->callGrpc(
                $this->transactionAddress,
                'transaction.TransactionService/ListCategories',
                json_encode([
                    'page'       => $page,
                    'page_size'  => $pageSize,
                    'sort_by'    => $sortBy,
                    'sort_order' => $sortOrder,
                    'search'     => $search,
                    'type'       => $type,
                ]),
            );

            Log::info('grpc_list_categories_success', [
                'service' => 'grpc_client',
                'total'   => $result['total'] ?? 0,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('grpc_list_categories_failed', [
                'service' => 'grpc_client',
                'error'   => $e->getMessage(),
            ]);
            return ['categories' => [], 'total' => 0, 'page' => $page, 'page_size' => $pageSize, 'total_pages' => 0];
        }
    }

    public function getCategoryDetail(string $id): ?array
    {
        try {
            $result = $this->callGrpc(
                $this->transactionAddress,
                'transaction.TransactionService/GetCategoryDetail',
                json_encode(['id' => $id]),
            );

            Log::info('grpc_get_category_detail_success', [
                'service' => 'grpc_client',
                'id'      => $id,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('grpc_get_category_detail_failed', [
                'service' => 'grpc_client',
                'id'      => $id,
                'error'   => $e->getMessage(),
            ]);
            return null;
        }
    }

    // ── Asset Codes ──

    public function listAssetCodes(int $page = 1, int $pageSize = 10, string $sortBy = 'code', string $sortOrder = 'asc', string $search = ''): array
    {
        try {
            $result = $this->callGrpc(
                $this->investmentAddress,
                'investment.InvestmentService/ListAssetCodes',
                json_encode([
                    'page'       => $page,
                    'page_size'  => $pageSize,
                    'sort_by'    => $sortBy,
                    'sort_order' => $sortOrder,
                    'search'     => $search,
                ]),
            );

            Log::info('grpc_list_asset_codes_success', [
                'service' => 'grpc_client',
                'total'   => $result['total'] ?? 0,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('grpc_list_asset_codes_failed', [
                'service' => 'grpc_client',
                'error'   => $e->getMessage(),
            ]);
            return ['asset_codes' => [], 'total' => 0, 'page' => $page, 'page_size' => $pageSize, 'total_pages' => 0];
        }
    }

    public function getAssetCodeDetail(string $code): ?array
    {
        try {
            $result = $this->callGrpc(
                $this->investmentAddress,
                'investment.InvestmentService/GetAssetCodeDetail',
                json_encode(['code' => $code]),
            );

            Log::info('grpc_get_asset_code_detail_success', [
                'service' => 'grpc_client',
                'code'    => $code,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('grpc_get_asset_code_detail_failed', [
                'service' => 'grpc_client',
                'code'    => $code,
                'error'   => $e->getMessage(),
            ]);
            return null;
        }
    }

    // ── Internal: grpcurl wrapper ──

    private function callGrpc(string $address, string $method, string $jsonData): array
    {
        $protoPath = base_path('protobuf');
        $parts     = explode('/', $method);
        $service   = explode('.', $parts[0])[0]; // e.g. 'wallet'

        $cmd = sprintf(
            'grpcurl -plaintext -import-path %s -proto %s/%s.proto -d %s %s %s 2>&1',
            escapeshellarg($protoPath),
            escapeshellarg($service),
            escapeshellarg($service),
            escapeshellarg($jsonData),
            escapeshellarg($address),
            escapeshellarg($method),
        );

        $output = shell_exec($cmd);

        if ($output === null) {
            throw new \RuntimeException("gRPC call failed: no output from grpcurl");
        }

        $decoded = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("gRPC call failed: invalid JSON response - " . trim($output));
        }

        return $decoded;
    }
}
