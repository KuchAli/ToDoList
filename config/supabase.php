<?php

require_once __DIR__ . '/../.env.php';

class SupabaseClient
{
    private $url;
    private $anon_key;
    private $service_role_key;
    private $table = 'kegiatan';

   public function __construct($url, $anon_key, $service_role_key = null)
    {
        if (empty($url)) {
            throw new Exception('SUPABASE_URL tidak ditemukan.');
        }

        if (empty($anon_key)) {
            throw new Exception('SUPABASE_ANON_KEY tidak ditemukan.');
        }

        $this->url = rtrim(trim($url), '/');
        $this->anon_key = trim($anon_key);
        $this->service_role_key = !empty($service_role_key)
            ? trim($service_role_key)
            : null;
    }

    /**
     * Request ke Supabase REST API
     */
    private function request($method, $query, $data = null, $useServiceRole = false)
    {
        $url = $this->url . '/rest/v1/' . $query;

        $key = $useServiceRole
            ? $this->service_role_key
            : $this->anon_key;

        if (empty($key)) {
            return [
                'success' => false,
                'error' => 'API Key tidak ditemukan',
                'code' => 0
            ];
        }

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'apikey: ' . $key,
            'Authorization: Bearer ' . $key
        ];

        if (in_array($method, ['POST', 'PATCH', 'PUT'])) {
            $headers[] = 'Prefer: return=representation';
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);

        if ($data !== null) {
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                json_encode($data)
            );
        }

       $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);

            return [
                'success' => false,
                'error' => $error,
                'code' => 0
            ];
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $json = json_decode($response, true);

        return [
            'success' => $http_code >= 200 && $http_code < 300,
            'data' => $json,
            'code' => $http_code,
            'response' => $response
        ];
    }

    /**
     * Tambah kegiatan
     */
    public function insertKegiatan($nama, $jenis, $tanggal, $status = 'proses')
    {
        $nama = trim($nama);

        if (empty($nama)) {
            return false;
        }

        // Cek apakah sudah ada
        $existing = $this->request(
            'GET',
            $this->table .
            '?select=id_kegiatan&nama_kegiatan=eq.' .
            rawurlencode($nama)
        );

        if (
            $existing['success'] &&
            is_array($existing['data']) &&
            count($existing['data']) > 0
        ) {
            return false;
        }

        $data = [
            'nama_kegiatan' => $nama,
            'jenis_kegiatan' => $jenis,
            'tanggal_kegiatan' => $tanggal,
            'status' => $status
        ];

        $result = $this->request(
            'POST',
            $this->table,
            $data,
            true
        );

        if (!$result['success']) {
            error_log(
                "Insert gagal: " .
                $result['response']
            );
        }

        return $result['success'];
    }

    /**
     * Update status kegiatan
     */
    public function updateStatusKegiatan($id, $status)
    {
        $data = [
            'status' => $status
        ];

        $result = $this->request(
            'PATCH',
            $this->table .
            '?id_kegiatan=eq.' .
            intval($id),
            $data,
            true
        );

        if (!$result['success']) {
            error_log(
                "Update gagal: " .
                $result['response']
            );
        }

        return $result['success'];
    }

    /**
     * Ambil data kegiatan
     */
    public function getKegiatan($tanggal = null, $limit = 3, $offset = 0)
    {
        $query = $this->table . '?';

        if (!empty($tanggal)) {
            $query .=
                'tanggal_kegiatan=eq.' .
                urlencode($tanggal) .
                '&';
        }

        $query .=
            'order=tanggal_kegiatan.desc' .
            '&limit=' . intval($limit) .
            '&offset=' . intval($offset);

        $result = $this->request('GET', $query);

        return (
            $result['success'] &&
            is_array($result['data'])
        )
            ? $result['data']
            : [];
    }

    /**
     * Hitung jumlah kegiatan
     */
    public function countKegiatan($tanggal = null)
    {
        $query = $this->table .
            '?select=id_kegiatan';

        if (!empty($tanggal)) {
            $query .=
                '&tanggal_kegiatan=eq.' .
                urlencode($tanggal);
        }

        $result = $this->request('GET', $query);

        return (
            $result['success'] &&
            is_array($result['data'])
        )
            ? count($result['data'])
            : 0;
    }

    /**
     * Ambil semua kegiatan
     */
    public function getAllKegiatan($tanggal = null)
    {
        $query = $this->table . '?';

        if (!empty($tanggal)) {
            $query .=
                'tanggal_kegiatan=eq.' .
                urlencode($tanggal) .
                '&';
        }

        $query .= 'order=tanggal_kegiatan.desc';

        $result = $this->request('GET', $query);

        return (
            $result['success'] &&
            is_array($result['data'])
        )
            ? $result['data']
            : [];
    }
}

/**
 * Inisialisasi client
 */
try {

    if (!isset($_ENV['SUPABASE_URL'])) {
        throw new Exception('SUPABASE_URL belum dimuat dari .env');
    }

    if (!isset($_ENV['SUPABASE_ANON_KEY'])) {
        throw new Exception('SUPABASE_ANON_KEY belum dimuat dari .env');
    }

    $supabase = new SupabaseClient(
        $_ENV['SUPABASE_URL'],
        $_ENV['SUPABASE_ANON_KEY'],
        $_ENV['SUPABASE_SERVICE_ROLE_KEY'] ?? null
    );

} catch (Exception $e) {

    die(
        '<h3>Supabase Configuration Error</h3>' .
        '<p>' . htmlspecialchars($e->getMessage()) . '</p>'
    );

}