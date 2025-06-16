<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$func = new lsp();

class lsp
{
    public function login($username, $password)
    {
        global $conn;

        $sql = "SELECT username, password, role, bidang FROM akun WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $query = mysqli_stmt_get_result($stmt);
        $rows = mysqli_num_rows($query);
        $assoc = mysqli_fetch_assoc($query);
        mysqli_stmt_close($stmt);

        if ($rows > 0) {
            if (hash('sha256', $password) === $assoc['password']) {
                return ['response' => 'positive', 'username' => $assoc['username'], 'role' => $assoc['role'], 'bidang' => $assoc['bidang']];
            } else {
                return ['response' => 'negative', 'alert' => 'Password Salah'];
            }
        } else {
            return ['response' => 'negative', 'alert' => 'Username atau Password Salah'];
        }
    }

    public function register($username, $password, $nama, $role, $bidang)
    {
        global $conn;

        $sql = "SELECT * FROM akun WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $query = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($query) > 0) {
            return ['response' => 'negative', 'alert' => 'Username sudah terdaftar'];
        } else {
            $hashedPassword = hash('sha256', $password);
            $sql = "INSERT INTO akun (username, password, namaLengkap, role, bidang) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $username, $hashedPassword, $nama, $role, $bidang);
            if (mysqli_stmt_execute($stmt)) {
                return ['response' => 'positive', 'alert' => 'Registrasi Berhasil!'];
            } else {
                return ['response' => 'negative', 'alert' => 'Terjadi kesalahan saat registrasi.'];
            }
            mysqli_stmt_close($stmt);
        }
    }

    public function updateAccount($id_akun, $username, $password, $namaLengkap, $role, $bidang = null)
    {
        global $conn;

        $sql = "UPDATE akun SET username = ?, password = ?, namaLengkap = ?, role = ?, bidang = ? WHERE id_akun = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $username, $password, $namaLengkap, $role, $bidang, $id_akun);

        if (mysqli_stmt_execute($stmt)) {
            return ['response' => 'positive', 'alert' => 'Akun berhasil diperbarui!'];
        } else {
            return ['response' => 'negative', 'alert' => 'Terjadi kesalahan saat memperbarui akun: ' . $conn->error];
        }
        mysqli_stmt_close($stmt);
    }

    public function getData($table)
    {
        global $conn;
        $sql = "SELECT * FROM $table";
        $query = mysqli_query($conn, $sql);
        return $query;
    }

    public function getDataById($table, $where, $id)
    {
        global $conn;
        $sql = "SELECT * FROM $table WHERE $where = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $assoc = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $assoc;
    }

    public function delete($table, $where, $whereValues)
    {
        global $conn;
        $sql = "DELETE FROM $table WHERE $where = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $whereValues);
        $query = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if ($query) {
            return ['response' => true, 'alert' => 'Data berhasil dihapus!'];
        } else {
            return ['response' => false, 'alert' => 'Error: ' . $conn->error];
        }
    }
}
