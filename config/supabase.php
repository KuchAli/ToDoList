<?php

// Load environment variables
require_once __DIR__ . '/../.env.php';

class SupabaseClient {
    private $url;
    private $anon_key;
    private $service_role_key;
    private $table = 'kegiatan';

    public function __construct($url, $anon_key, $service_role_key = null) {
        $this->url = rtrim($url, '/');
        $this->anon_key = $anon_key;
        $this->service_role_key = $service_role_key;
    }

    /**
     * Make HTTP request to Supabase REST API
     */
    private function request($method, $query, $data = null, $useServiceRole = false) {
        $url = $this->url . '/rest/v1/' . $query;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        // Headers
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . ($useServiceRole ? $this->service_role_key : $this->anon_key)
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Body for POST/PATCH/PUT
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => $error,
                'code' => 0
            ];
        }
        
        return [
            'success' => $http_code >= 200 && $http_code < 300,
            'data' => json_decode($response, true),
            'code' => $http_code,
            'response' => $response
        ];
    }

    /**
     * Insert new kegiatan (tambah kegiatan)
     */
    public function insertKegiatan($nama, $jenis, $tanggal, $status = 'proses') {
        // Check if kegiatan already exists
        $existing = $this->request('GET', "kegiatan?nama_kegiatan=eq.$nama&select=id_kegiatan");
        
        if ($existing['success'] && is_array($existing['data']) && count($existing['data']) > 0) {
            return false; // Kegiatan already exists
        }
        
        $data = [
            'nama_kegiatan' => $nama,
            'jenis_kegiatan' => $jenis,
            'tanggal_kegiatan' => $tanggal,
            'status' => $status
        ];
        
        $result = $this->request('POST', 'kegiatan', $data, true);
        return $result['success'];
    }

    /**
     * Update status kegiatan
     */
    public function updateStatusKegiatan($id, $status) {
        $data = ['status' => $status];
        $result = $this->request('PATCH', "kegiatan?id_kegiatan=eq.$id", $data, true);
        return $result['success'];
    }

    /**
     * Get kegiatan by tanggal with pagination
     */
    public function getKegiatan($tanggal = null, $limit = 3, $offset = 0) {
        $query = 'kegiatan?';
        
        if ($tanggal) {
            $query .= "tanggal_kegiatan=eq.$tanggal&";
        }
        
        $query .= "order=tanggal_kegiatan.desc&limit=$limit&offset=$offset";
        
        $result = $this->request('GET', $query);
        
        if ($result['success'] && is_array($result['data'])) {
            return $result['data'];
        }
        
        return [];
    }

    /**
     * Count total kegiatan
     */
    public function countKegiatan($tanggal = null) {
        $query = 'kegiatan?select=id_kegiatan';
        
        if ($tanggal) {
            $query .= "&tanggal_kegiatan=eq.$tanggal";
        }
        
        $result = $this->request('GET', $query);
        
        if ($result['success'] && is_array($result['data'])) {
            return count($result['data']);
        }
        
        return 0;
    }

    /**
     * Get all kegiatan by tanggal (without pagination)
     */
    public function getAllKegiatan($tanggal = null) {
        $query = 'kegiatan?';
        
        if ($tanggal) {
            $query .= "tanggal_kegiatan=eq.$tanggal&";
        }
        
        $query .= "order=tanggal_kegiatan.desc";
        
        $result = $this->request('GET', $query);
        
        if ($result['success'] && is_array($result['data'])) {
            return $result['data'];
        }
        
        return [];
    }
}

// Initialize client
$supabase_url = $_ENV['SUPABASE_URL'] ?? getenv('SUPABASE_URL');
$supabase_anon = $_ENV['SUPABASE_ANON_KEY'] ?? getenv('SUPABASE_ANON_KEY');
$supabase_service_role = $_ENV['SUPABASE_SERVICE_ROLE_KEY'] ?? getenv('SUPABASE_SERVICE_ROLE_KEY');

$supabase = new SupabaseClient($supabase_url, $supabase_anon, $supabase_service_role);

?>
