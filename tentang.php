<?php
include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Tentang Sistem Pendukung Keputusan</h4>
            </div>
            <div class="card-body">
                <h5>Apa itu Sistem Pendukung Keputusan?</h5>
                <p>
                    Sistem Pendukung Keputusan (SPK) adalah sistem informasi interaktif yang menyediakan informasi, pemodelan, dan manipulasi data yang digunakan untuk membantu pengambilan keputusan dalam situasi yang semi terstruktur dan tidak terstruktur.
                </p>
                
                <h5>Metode Forward Chaining</h5>
                <p>
                    Forward Chaining adalah metode inferensi yang digunakan dalam sistem pakar dan sistem berbasis aturan. Metode ini memulai dari sekumpulan fakta yang diketahui, kemudian menerapkan aturan untuk mendapatkan fakta baru, dan proses ini berlanjut sampai tujuan tercapai atau sampai tidak ada lagi aturan yang dapat diterapkan.
                </p>
                
                <h5>Cara Kerja Sistem</h5>
                <p>
                    Sistem ini bekerja dengan mengumpulkan informasi dari pengguna melalui serangkaian pertanyaan. Jawaban dari pertanyaan-pertanyaan tersebut akan diproses menggunakan metode Forward Chaining untuk menentukan jurusan yang paling sesuai berdasarkan minat dan kemampuan pengguna.
                </p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Jurusan SMA</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">IPA (Ilmu Pengetahuan Alam)</li>
                                    <li class="list-group-item">IPS (Ilmu Pengetahuan Sosial)</li>
                                    <li class="list-group-item">Bahasa</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Jurusan SMK</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Desain Grafis/Multimedia</li>
                                    <li class="list-group-item">Keperawatan</li>
                                    <li class="list-group-item">Akuntansi</li>
                                    <li class="list-group-item">Administrasi Perkantoran</li>
                                    <li class="list-group-item">Tata Boga</li>
                                    <li class="list-group-item">Tata Busana</li>
                                    <li class="list-group-item">Tata Rias/Kecantikan</li>
                                    <li class="list-group-item">Farmasi</li>
                                    <li class="list-group-item">Perhotelan dan Pariwisata</li>
                                    <li class="list-group-item">Pelayaran</li>
                                    <li class="list-group-item">Teknik Mesin</li>
                                    <li class="list-group-item">Teknik Komputer dan Jaringan (TKJ)</li>
                                    <li class="list-group-item">Teknik Otomotif</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h5>Referensi</h5>
                    <p>
                        Sistem ini dibuat berdasarkan referensi dari berbagai sumber, termasuk video tutorial dari YouTube: <a href="https://www.youtube.com/watch?v=Dyk3RIfg2RE" target="_blank">Sistem Pengambilan Keputusan untuk menentukan Jurusan dengan metode Forward Chaining</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>