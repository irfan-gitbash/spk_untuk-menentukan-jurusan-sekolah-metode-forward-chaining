<?php
include 'includes/header.php';

// Ambil semua jurusan
$jurusan_sma = [];
$jurusan_smk = [];

$all_jurusan = getAllJurusan();
foreach ($all_jurusan as $jurusan) {
    if ($jurusan['jenis_sekolah'] == 'SMA') {
        $jurusan_sma[] = $jurusan;
    } else {
        $jurusan_smk[] = $jurusan;
    }
}
?>

<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Daftar Jurusan</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Temukan informasi lengkap tentang berbagai jurusan di SMA dan SMK beserta prospek karir masa depan.
            </p>
        </div>

        <!-- Tab Buttons -->
        <div class="flex justify-center space-x-4 mb-12">
            <button class="tab-btn active px-8 py-3 rounded-xl bg-primary text-white font-semibold transition duration-200 hover:bg-secondary flex items-center space-x-2" data-tab="sma">
                <i class="fas fa-school"></i>
                <span>SMA</span>
            </button>
            <button class="tab-btn px-8 py-3 rounded-xl bg-gray-200 text-gray-700 font-semibold transition duration-200 hover:bg-gray-300 flex items-center space-x-2" data-tab="smk">
                <i class="fas fa-tools"></i>
                <span>SMK</span>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- SMA Section -->
            <div id="sma" class="tab-pane active">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($jurusan_sma as $jurusan): ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-primary/10 p-6">
                            <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-graduation-cap text-primary text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900"><?php echo $jurusan['nama_jurusan']; ?></h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-6"><?php echo $jurusan['deskripsi']; ?></p>
                            <div class="space-y-4">
                                <h4 class="font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-briefcase text-primary mr-2"></i>
                                    Prospek Karir
                                </h4>
                                <p class="text-gray-600 pl-6"><?php echo $jurusan['prospek_karir']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SMK Section -->
            <div id="smk" class="tab-pane hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($jurusan_smk as $jurusan): ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-primary/10 p-6">
                            <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-cog text-primary text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900"><?php echo $jurusan['nama_jurusan']; ?></h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-6"><?php echo $jurusan['deskripsi']; ?></p>
                            <div class="space-y-4">
                                <h4 class="font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-briefcase text-primary mr-2"></i>
                                    Prospek Karir
                                </h4>
                                <p class="text-gray-600 pl-6"><?php echo $jurusan['prospek_karir']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active classes
            tabBtns.forEach(b => {
                b.classList.remove('active', 'bg-primary', 'text-white');
                b.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
                pane.classList.remove('active');
            });
            
            // Add active classes
            this.classList.add('active', 'bg-primary', 'text-white');
            this.classList.remove('bg-gray-200', 'text-gray-700');
            
            const activePane = document.getElementById(tabId);
            activePane.classList.remove('hidden');
            activePane.classList.add('active');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
