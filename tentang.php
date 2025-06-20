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
                    
                    <!-- Tombol Export PDF dan Zoom -->
                    <div class="mb-4 flex flex-wrap gap-2">
                        <button id="exportPdfBtn" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg flex items-center">
                            <i class="fas fa-file-pdf mr-2"></i> Export Pohon Keputusan sebagai PDF
                        </button>
                        <div class="flex items-center space-x-2">
                            <button id="zoomInBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button id="zoomOutBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <button id="resetZoomBtn" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded-lg">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Mermaid Diagram Container -->
                    <div id="mermaidContainer" class="overflow-auto border rounded-lg p-4 bg-white" style="height: 60vh;">
                        <div id="mermaidDiagram" class="mermaid">
                            graph TD
                                Kecerdasan
                                Kecerdasan --> C1
                                C1 --> C2
                            C2 --> C3
                            C3 --> C4
                            C4 --> C5
                            C5 --> K1

                            Kecerdasan --> C6
                            C6 --> C7
                            C7 --> C8
                            C8 --> C9
                            C9 --> C10
                            C10 --> K2

                            Kecerdasan --> C11
                            C11 --> C12
                            C12 --> C13
                            C13 --> C14
                            C14 --> C15
                            C15 --> K3

                            Kecerdasan --> C16
                            C16 --> C17
                            C17 --> C18
                            C18 --> C19
                            C19 --> C20
                            C20 --> K4

                            Kecerdasan --> C21
                            C21 --> C22
                            C22 --> C23
                            C23 --> C24
                            C24 --> C25
                            C25 --> K5

                            Kecerdasan --> C26
                            C26 --> C27
                            C27 --> C28
                            C28 --> C29
                            C29 --> C30
                            C30 --> K6

                            Kecerdasan --> C31
                            C31 --> C32
                            C32 --> C33
                            C33 --> C34
                            C34 --> C35
                            C35 --> K7

                            Kecerdasan --> C36
                            C36 --> C37
                            C37 --> C38
                            C38 --> C39
                            C39 --> C40
                            C40 --> K8

                            Kecerdasan --> C41
                            C41 --> C42
                            C42 --> C43
                            C43 --> C44
                            C44 --> C45
                            C45 --> K9
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
        // Inisialisasi Mermaid dengan konfigurasi yang lebih ringkas
        mermaid.initialize({
            startOnLoad: true,
            theme: 'default',
            securityLevel: 'loose',
            flowchart: {
                useMaxWidth: false, // Set false agar bisa di-zoom
                htmlLabels: true,
                curve: 'linear',
                nodeSpacing: 40,    // Jarak antar node
                rankSpacing: 60     // Jarak antar level
            },
            fontFamily: '"Inter", sans-serif',
            fontSize: 12 // Ukuran font diperbesar dari 14 ke 16
        });

        const mermaidContainer = document.getElementById('mermaidContainer');
        const mermaidDiagram = document.getElementById('mermaidDiagram');
        let currentZoom = 1.0;
        const zoomStep = 0.1;

        function applyZoom() {
            mermaidDiagram.style.transform = `scale(${currentZoom})`;
            mermaidDiagram.style.transformOrigin = 'top left';
        }

        document.getElementById('zoomInBtn').addEventListener('click', () => {
            currentZoom += zoomStep;
            applyZoom();
        });

        document.getElementById('zoomOutBtn').addEventListener('click', () => {
            if (currentZoom > zoomStep) {
                currentZoom -= zoomStep;
                applyZoom();
            }
        });

        document.getElementById('resetZoomBtn').addEventListener('click', () => {
            currentZoom = 1.0;
            applyZoom();
        });
        
        // Fungsi untuk export PDF
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            // Tampilkan pesan loading
            const loadingMsg = document.createElement('div');
            loadingMsg.innerHTML = '<div class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 z-50"><div class="bg-white p-4 rounded-lg"><i class="fas fa-spinner fa-spin mr-2"></i>Sedang membuat PDF...</div></div>';
            document.body.appendChild(loadingMsg);
            
            // Reset zoom dan transformasi sebelum mengambil gambar
            const mermaidDiv = document.getElementById('mermaidDiagram');
            const originalTransform = mermaidDiv.style.transform;
            const originalTransformOrigin = mermaidDiv.style.transformOrigin;
            mermaidDiv.style.transform = 'scale(1)';
            mermaidDiv.style.transformOrigin = 'top left';
            
            // Berikan waktu untuk rendering diagram
            setTimeout(function() {
                const { jsPDF } = window.jspdf;
                
                // Pastikan diagram terlihat penuh dengan menyesuaikan ukuran container
                const svgElement = mermaidDiv.querySelector('svg');
                if (svgElement) {
                    // Simpan ukuran asli
                    const originalWidth = svgElement.style.minWidth;
                    const originalHeight = svgElement.getAttribute('height');
                    const originalFontSize = svgElement.style.fontSize;
                    
                    // Sesuaikan untuk ekspor
                    svgElement.style.minWidth = '2000px';
                    svgElement.setAttribute('width', '2000px');
                    svgElement.setAttribute('height', '2000px');
                    svgElement.style.fontSize = '28px';
                }
                
                html2canvas(mermaidDiv, {
                    scale: 5, // Meningkatkan kualitas gambar (dari 4)
                    backgroundColor: '#ffffff',
                    useCORS: true,
                    logging: true,
                    allowTaint: true,
                    width: mermaidDiv.scrollWidth, // Gunakan lebar penuh
                    height: mermaidDiv.scrollHeight, // Gunakan tinggi penuh
                    windowWidth: 2000, // Lebar jendela virtual lebih besar
                    windowHeight: 2000 // Tinggi jendela virtual lebih besar
                }).then(function(canvas) {
                    const imgData = canvas.toDataURL('image/png', 1.0); // Kualitas maksimum
                    
                    // Gunakan ukuran A2 untuk PDF yang lebih besar
                    const pdf = new jsPDF('l', 'mm', 'a2'); // Landscape orientation, A2 size
                    
                    // Ukuran halaman A2 landscape
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = pdf.internal.pageSize.getHeight();
                    
                    // Menghitung rasio untuk memastikan diagram muat di halaman
                    const imgWidth = canvas.width;
                    const imgHeight = canvas.height;
                    const ratio = Math.min(pdfWidth / imgWidth, pdfHeight / imgHeight) * 0.95; // Sedikit margin
                    
                    // Menambahkan judul
                    pdf.setFontSize(24); // Font judul
                    pdf.text('Pohon Keputusan Forward Chaining', pdfWidth/2, 15, { align: 'center' });
                    pdf.setFontSize(18); // Font subjudul
                    pdf.text('Sistem Pendukung Keputusan Pemilihan Jurusan SMA dan SMK', pdfWidth/2, 25, { align: 'center' });
                    
                    // Menambahkan gambar diagram dengan posisi yang disesuaikan
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
                    pdf.setFontSize(14); // Font footer
                    pdf.text('Dicetak pada: ' + dateStr, pdfWidth - 20, pdfHeight - 10, { align: 'right' });
                    
                    // Simpan PDF dengan nama yang lebih deskriptif
                    pdf.save('Pohon_Keputusan_Forward_Chaining_' + dateStr.replace(/\//g, '-') + '.pdf');
                    
                    // Kembalikan ukuran asli jika ada perubahan
                    if (svgElement) {
                        svgElement.style.minWidth = originalWidth;
                        svgElement.setAttribute('height', originalHeight);
                        svgElement.style.fontSize = originalFontSize;
                    }
                    
                    // Kembalikan transformasi asli
                    mermaidDiv.style.transform = originalTransform;
                    mermaidDiv.style.transformOrigin = originalTransformOrigin;
                    
                    // Hapus pesan loading
                    document.body.removeChild(loadingMsg);
                });
            }, 3000); // Berikan waktu lebih lama untuk rendering (dari 2000)
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
