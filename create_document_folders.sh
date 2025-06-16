#!/bin/bash

# Script untuk membuat folder dokumen untuk aplikasi PHP
# Direktori induk
BASE_DIR="assets/dokumen"

# Daftar subfolder yang akan dibuat
FOLDERS=(
    "sub_bagian_pelayanan_publik"
    "seksi_ketentraman_dan_ketertiban_umum"
    "seksi_pembangunan_dan_pemberdayaan_masyarakat"
    "seksi_pemerintahan"
    "sub_bagian_umum_dan_kepegawaian"
    "sub_bagian_perencanaan_evaluasi_dan_keuangan"
)

# Buat direktori induk jika belum ada
if [ ! -d "$BASE_DIR" ]; then
    mkdir -p "$BASE_DIR"
    chmod 755 "$BASE_DIR"
    echo "Membuat direktori induk: $BASE_DIR"
else
    echo "Direktori induk sudah ada: $BASE_DIR"
fi

# Loop untuk membuat setiap subfolder
for FOLDER in "${FOLDERS[@]}"; do
    FULL_PATH="$BASE_DIR/$FOLDER"
    if [ ! -d "$FULL_PATH" ]; then
        mkdir -p "$FULL_PATH"
        chmod 755 "$FULL_PATH"
        echo "Membuat direktori: $FULL_PATH"
    else
        echo "Direktori sudah ada: $FULL_PATH"
    fi
done

echo "Proses selesai. Semua direktori telah diperiksa/dibuat dengan izin 755."
