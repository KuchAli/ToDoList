<?php

// Load Supabase configuration
require_once __DIR__ . '/../config/supabase.php';

// Wrapper functions untuk Supabase client
// Format sama seperti sebelumnya, tapi menggunakan Supabase di belakangnya

function kegiatan($conn, $nama, $jenis, $tanggal, $status) {
    global $supabase;
    
    $nama = trim($nama);
    
    if (empty($nama)) {
        return false;
    }
    
    return $supabase->insertKegiatan($nama, $jenis, $tanggal, $status);
}

function updateStatus($conn, $status, $id) {
    global $supabase;
    
    if (empty($id) || empty($status)) {
        return false;
    }
    
    return $supabase->updateStatusKegiatan($id, $status);
}

function getKegiatan($conn, $tanggal = null, $limit = 3, $offset = 0) {
    global $supabase;
    
    $data = $supabase->getKegiatan($tanggal, $limit, $offset);
    
    // Convert array to mysqli result-like object for compatibility
    if (is_array($data)) {
        return new SupabaseResult($data);
    }
    
    return new SupabaseResult([]);
}

function countKegiatan($conn, $tanggal = null) {
    global $supabase;
    
    return $supabase->countKegiatan($tanggal);
}

/**
 * Wrapper class to make Supabase results compatible with mysqli_fetch_array
 */
class SupabaseResult {
    private $data;
    private $position = 0;
    
    public function __construct($data) {
        $this->data = is_array($data) ? $data : [];
    }
    
    public function fetch_array($result_type = MYSQLI_ASSOC) {
        if ($this->position >= count($this->data)) {
            return null;
        }
        
        $row = $this->data[$this->position];
        $this->position++;
        
        return $row;
    }
    
    public function num_rows() {
        return count($this->data);
    }
}

// Compatibility function for mysqli_num_rows
function mysqli_num_rows($result) {
    if ($result instanceof SupabaseResult) {
        return $result->num_rows();
    }
    return 0;
}

// Compatibility function for mysqli_fetch_array
function mysqli_fetch_array($result) {
    if ($result instanceof SupabaseResult) {
        return $result->fetch_array();
    }
    return null;
}

?>