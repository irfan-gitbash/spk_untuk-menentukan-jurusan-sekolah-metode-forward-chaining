<?php include 'includes/header.php'; ?>

<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Tentang Sistem Pendukung Keputusan</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Pelajari lebih lanjut tentang sistem kami dan bagaimana kami membantu Anda memilih jurusan yang tepat.
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <!-- Main Content Grid -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-8">
                    <!-- About SPK Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-brain text-primary text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Apa itu Sistem Pendukung Keputusan?</h2>
                        </div>
                        <p class="text-gray-600">
                            Sistem Pendukung Keputusan (SPK) adalah sistem informasi interaktif yang menyediakan informasi, pemodelan, dan manipulasi data yang digunakan untuk membantu pengambilan keputusan dalam situasi yang semi terstruktur dan tidak terstruktur.
                        </p>
                    </div>

                    <!-- Forward Chaining Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-code-branch text-primary text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Metode Forward Chaining</h2>
                        </div>
                        <p class="text-gray-600">
                            Forward Chaining adalah metode inferensi yang digunakan dalam sistem pakar dan sistem berbasis aturan. Metode ini memulai dari sekumpulan fakta yang diketahui, kemudian menerapkan aturan untuk mendapatkan fakta baru, dan proses ini berlanjut sampai tujuan tercapai atau sampai tidak ada lagi aturan yang dapat diterapkan.
                        </p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-8">
                    <!-- How It Works Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-cogs text-primary text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Cara Kerja Sistem</h2>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Sistem ini bekerja dengan mengumpulkan informasi dari pengguna melalui serangkaian pertanyaan. Jawaban dari pertanyaan-pertanyaan tersebut akan diproses menggunakan metode Forward Chaining untuk menentukan jurusan yang paling sesuai berdasarkan minat dan kemampuan pengguna.
                        </p>
                    </div>

                    <!-- Available Majors Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-primary text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Jurusan Tersedia</h2>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <!-- SMA Section -->
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-3">Jurusan SMA</h3>
                                <ul class="space-y-2">
                                    <li class="bg-gray-50 p-2 rounded-lg text-sm text-gray-600">IPA (Ilmu Pengetahuan Alam)</li>
                                    <li class="bg-gray-50 p-2 rounded-lg text-sm text-gray-600">IPS (Ilmu Pengetahuan Sosial)</li>
                                    <li class="bg-gray-50 p-2 rounded-lg text-sm text-gray-600">Bahasa</li>
                                </ul>
                            </div>

                            <!-- SMK Section -->
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-3">Jurusan SMK</h3>
                                <ul class="space-y-2">
                                    <li class="bg-gray-50 p-2 rounded-lg text-sm text-gray-600">Teknik Komputer</li>
                                    <li class="bg-gray-50 p-2 rounded-lg text-sm text-gray-600">Akuntansi</li>
                                    <li class="bg-gray-50 p-2 rounded-lg text-sm text-gray-600">Multimedia</li>
                                    <li class="bg-gray-50 p-2 rounded-lg text-sm text-gray-600">Dan lainnya...</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pohon Keputusan Section (BARU) -->
            <div class="mt-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-sitemap text-primary text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Pohon Keputusan Forward Chaining</h2>
                    </div>
                    <p class="text-gray-600 mb-6">
                        Berikut adalah visualisasi pohon keputusan yang digunakan dalam sistem pendukung keputusan untuk menentukan jurusan SMA dan SMK menggunakan metode forward chaining.
                    </p>
                    
                    <!-- Tombol Export PDF -->
                    <div class="mb-4">
                        <button id="exportPdfBtn" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg flex items-center">
                            <i class="fas fa-file-pdf mr-2"></i> Export Pohon Keputusan sebagai PDF
                        </button>
                    </div>
                    
                    <!-- Mermaid Diagram -->
                    <div id="mermaidDiagram" class="mermaid overflow-auto" style="background-color: white;">
                        graph TD
                            A[Start] --> B[Input Data Siswa]
                            B --> C[Jawab Pertanyaan]
                            C --> D{Proses Forward Chaining}
                            
                            %% Proses Forward Chaining
                            D --> E[Hitung Skor Kecerdasan]
                            E --> F[Tentukan Kecerdasan Dominan]
                            F --> G[Cari Jurusan Terkait]
                            
                            %% Kecerdasan dan Jurusan Terkait
                            G --> K1{Kecerdasan Linguistic-Verbal}
                            G --> K2{Kecerdasan Logika-Matematika}
                            G --> K3{Kecerdasan Spasial-Visual}
                            G --> K4{Kecerdasan Kinetik}
                            G --> K5{Kecerdasan Ritmik-Musik}
                            G --> K6{Kecerdasan Interpersonal}
                            G --> K7{Kecerdasan Intrapersonal}
                            G --> K8{Kecerdasan Naturalis}
                            G --> K9{Kecerdasan Eksistensial}
                            
                            %% Jurusan SMA dan SMK untuk K1
                            K1 --> K1_SMA[SMA: IPS, Bahasa]
                            K1 --> K1_SMK[SMK: Administrasi Perkantoran, Perhotelan, Pemasaran]
                            
                            %% Jurusan SMA dan SMK untuk K2
                            K2 --> K2_SMA[SMA: IPA]
                            K2 --> K2_SMK[SMK: Akuntansi, Farmasi, TKJ, Otomotif]
                            
                            %% Jurusan SMA dan SMK untuk K3
                            K3 --> K3_SMK[SMK: DKV, Multimedia, Tata Rias, Tata Busana]
                            
                            %% Jurusan SMA dan SMK untuk K4
                            K4 --> K4_SMK[SMK: Teknik Mesin, Tata Boga, Pelayaran, Otomotif]
                            
                            %% Jurusan SMA dan SMK untuk K5
                            K5 --> K5_SMK[SMK: Multimedia, Tata Rias, Perhotelan]
                            
                            %% Jurusan SMA dan SMK untuk K6
                            K6 --> K6_SMA[SMA: IPS]
                            K6 --> K6_SMK[SMK: Keperawatan, Administrasi Perkantoran, Perhotelan, Pemasaran]
                            
                            %% Jurusan SMA dan SMK untuk K7
                            K7 --> K7_SMA[SMA: IPA, IPS]
                            K7 --> K7_SMK[SMK: Keperawatan, Farmasi]
                            
                            %% Jurusan SMA dan SMK untuk K8
                            K8 --> K8_SMA[SMA: IPA]
                            K8 --> K8_SMK[SMK: Keperawatan, Farmasi, Tata Boga]
                            
                            %% Jurusan SMA dan SMK untuk K9
                            K9 --> K9_SMA[SMA: IPS, Bahasa]
                            K9 --> K9_SMK[SMK: Administrasi Perkantoran, TKJ, Tata Rias]
                            
                            %% Hasil Akhir
                            K1_SMA --> H[Hasil Rekomendasi Jurusan]
                            K1_SMK --> H
                            K2_SMA --> H
                            K2_SMK --> H
                            K3_SMK --> H
                            K4_SMK --> H
                            K5_SMK --> H
                            K6_SMA --> H
                            K6_SMK --> H
                            K7_SMA --> H
                            K7_SMK --> H
                            K8_SMA --> H
                            K8_SMK --> H
                            K9_SMA --> H
                            K9_SMK --> H
                            
                            H --> I[Tampilkan Hasil]
                            I --> J[Selesai]
                    </div>
                </div>
            </div>

            <!-- Reference Section -->
            <div class="mt-8">
                <div class="bg-primary/5 border-l-4 border-primary rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Referensi</h3>
                    <p class="text-gray-600">
                        Sistem ini dibuat berdasarkan tugas akhir skripsi serta berjudul Sistem Pendukung Keputusan untuk menentukan Jurusan SMA dan SMK dengan metode Forward Chaining.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan Mermaid JS -->
<script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
<!-- Tambahkan html2canvas dan jsPDF untuk export PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Mermaid dengan ukuran yang lebih kecil
        mermaid.initialize({
            startOnLoad: true,
            theme: 'default',
            securityLevel: 'loose',
            flowchart: {
                useMaxWidth: true,
                htmlLabels: true,
                curve: 'basis',
                nodeSpacing: 20,  // Mengurangi jarak antar node
                rankSpacing: 30,  // Mengurangi jarak antar level
                fontSize: 11      // Mengurangi ukuran font
            },
            fontSize: 11,         // Ukuran font global lebih kecil
        });
        
        // Fungsi untuk export PDF
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            // Tampilkan pesan loading
            const loadingMsg = document.createElement('div');
            loadingMsg.innerHTML = '<div class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 z-50"><div class="bg-white p-4 rounded-lg"><i class="fas fa-spinner fa-spin mr-2"></i>Sedang membuat PDF...</div></div>';
            document.body.appendChild(loadingMsg);
            
            // Berikan waktu untuk rendering diagram
            setTimeout(function() {
                const { jsPDF } = window.jspdf;
                const diagram = document.getElementById('mermaidDiagram');
                
                html2canvas(diagram, {
                    scale: 2, // Meningkatkan kualitas gambar
                    backgroundColor: '#ffffff'
                }).then(function(canvas) {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jsPDF('l', 'mm', 'a4'); // Landscape orientation
                    
                    // Ukuran halaman A4 landscape
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = pdf.internal.pageSize.getHeight();
                    
                    // Menghitung rasio untuk memastikan diagram muat di halaman
                    const imgWidth = canvas.width;
                    const imgHeight = canvas.height;
                    const ratio = Math.min(pdfWidth / imgWidth, pdfHeight / imgHeight) * 0.9;
                    
                    // Menambahkan judul
                    pdf.setFontSize(16);
                    pdf.text('Pohon Keputusan Forward Chaining', pdfWidth/2, 15, { align: 'center' });
                    pdf.setFontSize(12);
                    pdf.text('Sistem Pendukung Keputusan Pemilihan Jurusan SMA dan SMK', pdfWidth/2, 22, { align: 'center' });
                    
                    // Menambahkan gambar diagram
                    pdf.addImage(
                        imgData, 
                        'PNG', 
                        (pdfWidth - imgWidth * ratio) / 2, // Posisi X (tengah)
                        30, // Posisi Y (beri ruang untuk judul)
                        imgWidth * ratio, 
                        imgHeight * ratio
                    );
                    
                    // Menambahkan footer
                    const today = new Date();
                    const dateStr = today.toLocaleDateString('id-ID');
                    pdf.setFontSize(10);
                    pdf.text('Dicetak pada: ' + dateStr, pdfWidth - 20, pdfHeight - 10, { align: 'right' });
                    
                    // Simpan PDF
                    pdf.save('Pohon_Keputusan_Forward_Chaining.pdf');
                    
                    // Hapus pesan loading
                    document.body.removeChild(loadingMsg);
                });
            }, 1000);
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
