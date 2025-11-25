<?php
namespace App\Services\Mikrotiks;

use App\Repositories\Users\UserWiFiAccountRepository;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConnectioService
{


    public function __construct(private UserWiFiAccountRepository $userWiFiAccountRepository)
    {

    }

    public function setLeasesDhcp(string $userWifiAccountId)
    {
        $userWifiAccount = $this->userWiFiAccountRepository->getUserWiFIAccountById($userWifiAccountId);
        if (!$userWifiAccount) {
            throw new \RuntimeException("User WiFi account not found: {$userWifiAccountId}");
        }

        $nextIp = $this->getAvalibleIp();
        $payload = [
            'address' => $nextIp,
            'mac-address' => $userWifiAccount->mac,
            'comment' => $userWifiAccount->userWifi->kind,
        ];

        $base = rtrim(env('ROUTER_REST_DOMAIN'), '/');
        $url = "{$base}/rest/ip/dhcp-server/lease";

        try {
            $response = Http::asJson()
                ->withBasicAuth(env('ROUTER_USERNAME'), env('ROUTER_PASSWORD'))
                ->timeout(10)
                ->retry(3, 200)
                ->put($url, $payload)
                ->throw();
            $response = $response->json();
            $this->userWiFiAccountRepository->updateUserWiFIAccountById($userWifiAccountId, [
                'ip' => $response['address'],
                'leases_id' => $response['.id'],
                'status' => 'SYNC'
            ]);
            return $response;
        } catch (Exception $e) {
            Log::error('Failed to create DHCP static lease', [
                'url' => $url,
                'payload' => $payload,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            if (isset($response) && method_exists($response, 'body')) {
                Log::error('RouterOS response body: ' . $response->body());
            }
            throw $e;
        }
    }
    public function deleteLeasesDhcp(string $userWifiAccountId)
    {
        $userWifiAccount = $this->userWiFiAccountRepository->getUserWiFIAccountById($userWifiAccountId);
        if (!$userWifiAccount) {
            throw new \RuntimeException("User WiFi account not found: {$userWifiAccountId}");
        }


        $base = rtrim(env('ROUTER_REST_DOMAIN'), '/');
        $url = "{$base}/rest/ip/dhcp-server/lease/{$userWifiAccount->leases_id}";

        try {
            $response = Http::asJson()
                ->withBasicAuth(env('ROUTER_USERNAME'), env('ROUTER_PASSWORD'))
                ->timeout(10)
                ->retry(3, 200)
                ->delete($url)
                ->throw();

            $response = $response->json();
        } catch (\Throwable $e) {
            Log::error('Failed to delete DHCP static lease', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            if (isset($response) && method_exists($response, 'body')) {
                Log::error('RouterOS response body: ' . $response->body());
            }
            throw $e;
        }
    }

    public function setStatusLeasesDhcp(string $userWifiAccountId, $status)
    {
        $userWifiAccount = $this->userWiFiAccountRepository->getUserWiFIAccountById($userWifiAccountId);
        if (!$userWifiAccount) {
            throw new \RuntimeException("User WiFi account not found: {$userWifiAccountId}");
        }


        $base = rtrim(env('ROUTER_REST_DOMAIN'), '/');
        $url = "{$base}/rest/ip/dhcp-server/lease/{$userWifiAccount->leases_id}";

        $payload = [
            'disabled' => !$status,
            'comment' => $userWifiAccount?->userWifi?->kind
        ];

        try {
            $response = Http::asJson()
                ->withBasicAuth(env('ROUTER_USERNAME'), env('ROUTER_PASSWORD'))
                ->timeout(10)
                ->retry(3, 200)
                ->patch($url, $payload)
                ->throw();

            $response = $response->json();
        } catch (\Throwable $e) {
            Log::error('Failed to create DHCP static lease', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            if (isset($response) && method_exists($response, 'body')) {
                Log::error('RouterOS response body: ' . $response->body());
            }
            throw $e;
        }
    }


    public function getAvalibleIp()
    {
        $baseIps = explode('/', env('ROUTER_IP_ADDRESS_NETWORK'));
        $usedIps = $this->userWiFiAccountRepository->getUserWiFiAccountIps();
        if (count($baseIps) > 0) {
            $defaultGateway = $baseIps[0];
            $subnet = $baseIps[1];

            $ipLong = ip2long($defaultGateway);
            $mask = -1 << (32 - $subnet);
            $network = $ipLong & $mask;
            $broadcast = $network | (~$mask);

            $availableIps = collect();

            for ($i = $network + 1; $i < $broadcast; $i++) {
                $ip = long2ip($i);

                // skip gateway
                if ($ip === $defaultGateway) {
                    continue;
                }

                if (!in_array($ip, $usedIps)) {
                    $availableIps->push($ip);
                }
            }

            return $availableIps->first();
        }

        return null;
    }
}
