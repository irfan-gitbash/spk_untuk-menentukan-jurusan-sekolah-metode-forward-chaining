<?php
require_once 'includes/functions.php';

// Cek apakah ada ID siswa
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: konsultasi.php");
    exit;
}

$id_siswa = (int) $_GET['id'];
$siswa = getSiswaById($id_siswa);

// Jika siswa tidak ditemukan
if (!$siswa) {
    header("Location: konsultasi.php");
    exit;
}

// Ambil semua pertanyaan
$pertanyaan = getAllPertanyaan();

// Cek apakah ada pertanyaan aktif
$current_question = 0;
if (isset($_GET['q'])) {
    $current_question = (int) $_GET['q'];
}

// Jika form jawaban disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_jawaban'])) {
    $id_pertanyaan = (int) $_POST['id_pertanyaan'];
    $jawaban = clean($_POST['submit_jawaban']);
    
    // Simpan jawaban
    saveJawaban($id_siswa, $id_pertanyaan, $jawaban);
    
    // Jika ini adalah pertanyaan pertama (P01) dan jawabannya Tidak
    if ($current_question === 0 && $jawaban === 'Tidak') {
        header("Location: question.php?id=$id_siswa&smk_confirm=1");
        exit;
    }
    
    // Jika ini adalah konfirmasi SMK dan jawabannya Ya
    if (isset($_GET['smk_confirm']) && $jawaban === 'Ya') {
        // Simpan jawaban "Tidak" untuk pertanyaan SMA (P01)
        saveJawaban($id_siswa, 1, 'Tidak');
        
        // Mulai dengan pertanyaan minat dasar SMK
        header("Location: question.php?id=$id_siswa&q=10&smk=1"); // P11 untuk SMK
        exit;
    }
    
    // Handle pertanyaan SMK
    if (isset($_GET['smk'])) {
        // Untuk SMK, kita mulai dari P11 (index 10) sampai P23 (index 22)
        if ($current_question < 10) {
            $current_question = 10; // Mulai dari P11
        }
        
        $next_q = $current_question + 1;
        
        // Jika sudah menjawab sampai P23
        if ($current_question >= 22) {
            $hasil = forwardChaining($id_siswa);
            header("Location: hasil.php?id=$id_siswa");
            exit;
        }
        
        // Lanjut ke pertanyaan SMK berikutnya
        header("Location: question.php?id=$id_siswa&q=$next_q&smk=1");
        exit;
    }
    
    // Handle pertanyaan SMA
    if ($current_question < 9) { // Hanya sampai pertanyaan ke-10
        header("Location: question.php?id=$id_siswa&q=" . ($current_question + 1));
        exit;
    } else {
        // Jika sudah 10 pertanyaan
        $hasil = forwardChaining($id_siswa);
        header("Location: hasil.php?id=$id_siswa");
        exit;
    }
}

// Handle different question types
$is_smk_confirmation = false;
$is_smk_path = isset($_GET['smk']);

if (isset($_GET['smk_confirm'])) {
    $is_smk_confirmation = true;
    $current_pertanyaan = [
        'id_pertanyaan' => 0,
        'isi_pertanyaan' => 'Apakah Anda berminat untuk masuk SMK?'
    ];
} else {
    // Get current question
    $current_pertanyaan = $pertanyaan[$current_question];
    
    // Modify first question for SMA
    if ($current_question === 0 && !$is_smk_path) {
        $current_pertanyaan['isi_pertanyaan'] = "Apakah Anda berminat untuk masuk SMA?";
    }
    // Add context for SMK questions
    else if ($is_smk_path && $current_question >= 10) {
        $current_pertanyaan['isi_pertanyaan'] = "Untuk jurusan SMK: " . $current_pertanyaan['isi_pertanyaan'];
    }
}

$nama = $siswa['nama_lengkap'];
$umur = rand(15, 18); // Simulasi umur untuk tampilan

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Pertanyaan Diagnosis</h4>
            </div>
            <div class="card-body text-center">
                <h3>Hai, <?php echo $nama; ?> (<?php echo $umur; ?> th)</h3>
                
                <h4 class="my-4"><?php echo $current_pertanyaan['isi_pertanyaan']; ?></h4>
                
                <?php if ($is_smk_confirmation): ?>
                    <form method="post" action="">
                        <input type="hidden" name="id_pertanyaan" value="1"> <!-- P01 -->
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" name="submit_jawaban" value="Ya" class="btn btn-outline-primary btn-lg">Ya</button>
                            <a href="hasil.php?id=<?php echo $id_siswa; ?>" class="btn btn-outline-secondary btn-lg">Tidak</a>
                        </div>
                    </form>
                <?php else: ?>
                    <form method="post" action="">
                        <input type="hidden" name="id_pertanyaan" value="<?php echo $current_pertanyaan['id_pertanyaan']; ?>">
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" name="submit_jawaban" value="Ya" class="btn btn-outline-primary btn-lg">Ya</button>
                            <?php if ($current_question === 0): ?>
                                <button type="submit" name="submit_jawaban" value="Tidak" class="btn btn-outline-secondary btn-lg" 
                                    onclick="window.location.href='question.php?id=<?php echo $id_siswa; ?>&smk_confirm=1'; return false;">Tidak</button>
                            <?php else: ?>
                                <button type="submit" name="submit_jawaban" value="Tidak" class="btn btn-outline-secondary btn-lg">Tidak</button>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
                
                <?php if (!$is_smk_confirmation): ?>
                    <div class="progress mt-4">
                        <?php
                        // Calculate progress differently for SMK path
                        if ($is_smk_path) {
                            $total_questions = 13; // Total 13 questions for SMK (P11-P23)
                            $current = $current_question - 10 + 1; // Adjust for P11 starting at index 10
                            $progress = ($current / $total_questions) * 100;
                            echo '<div class="progress-bar" role="progressbar" style="width: ' . $progress . '%" ';
                            echo 'aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100">';
                            echo $current . '/13';
                        } else {
                            $progress = (($current_question + 1) / 10) * 100; // Only first 10 questions for SMA
                            echo '<div class="progress-bar" role="progressbar" style="width: ' . $progress . '%" ';
                            echo 'aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100">';
                            echo ($current_question + 1) . '/10';
                        }
                        ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>