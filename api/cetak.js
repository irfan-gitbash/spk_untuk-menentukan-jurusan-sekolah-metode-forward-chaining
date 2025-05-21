const mysql = require("./db");

module.exports = async (req, res) => {
  // Enable CORS
  res.setHeader("Access-Control-Allow-Credentials", true);
  res.setHeader("Access-Control-Allow-Origin", "*");
  res.setHeader("Access-Control-Allow-Methods", "GET,OPTIONS");
  res.setHeader(
    "Access-Control-Allow-Headers",
    "X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version"
  );

  // Handle OPTIONS request
  if (req.method === "OPTIONS") {
    res.status(200).end();
    return;
  }

  // Handle GET request
  if (req.method === "GET") {
    try {
      const { id } = req.query;

      if (!id) {
        return res.status(400).json({
          error: "ID siswa tidak ditemukan",
        });
      }

      const query = `
                SELECT 
                    k.*,
                    j.nama_jurusan,
                    j.jenis_sekolah,
                    j.deskripsi as deskripsi_jurusan,
                    j.prospek_karir
                FROM konsultasi k
                JOIN jurusan j ON k.id_jurusan = j.id_jurusan
                WHERE k.id_siswa = ?
            `;

      const result = await mysql.query(query, [id]);
      await mysql.end();

      if (result.length === 0) {
        return res.status(404).json({
          error: "Data tidak ditemukan",
        });
      }

      const data = result[0];

      // Generate HTML for printing
      const html = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Hasil Konsultasi - ${data.nama_lengkap}</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .subtitle { font-size: 18px; color: #666; }
                        .section { margin-bottom: 20px; }
                        .section-title { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
                        .info-row { margin-bottom: 10px; }
                        .label { font-weight: bold; min-width: 150px; display: inline-block; }
                        .value { color: #333; }
                        .footer { margin-top: 50px; text-align: center; font-size: 14px; color: #666; }
                        @media print {
                            body { padding: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="title">Hasil Konsultasi Pemilihan Jurusan</div>
                        <div class="subtitle">Sistem Pendukung Keputusan</div>
                    </div>

                    <div class="section">
                        <div class="section-title">Data Siswa</div>
                        <div class="info-row">
                            <span class="label">Nama Lengkap:</span>
                            <span class="value">${data.nama_lengkap}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">NISN:</span>
                            <span class="value">${data.nisn}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Sekolah:</span>
                            <span class="value">${data.sekolah}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Kelas:</span>
                            <span class="value">${data.kelas}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Tanggal Konsultasi:</span>
                            <span class="value">${new Date(
                              data.tanggal
                            ).toLocaleString("id-ID")}</span>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Hasil Rekomendasi</div>
                        <div class="info-row">
                            <span class="label">Jenis Sekolah:</span>
                            <span class="value">${data.jenis_sekolah}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Jurusan:</span>
                            <span class="value">${data.nama_jurusan}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Deskripsi:</span>
                            <span class="value">${data.deskripsi_jurusan}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Prospek Karir:</span>
                            <span class="value">${data.prospek_karir}</span>
                        </div>
                    </div>

                    <div class="footer">
                        Dicetak pada: ${new Date().toLocaleString("id-ID")}
                    </div>

                    <script>
                        window.onload = () => {
                            window.print();
                        };
                    </script>
                </body>
                </html>
            `;

      res.setHeader("Content-Type", "text/html");
      res.status(200).send(html);
    } catch (error) {
      console.error("Database error:", error);
      return res.status(500).json({
        error: "Terjadi kesalahan saat mencetak hasil konsultasi",
      });
    }
  }

  // Handle unsupported methods
  return res.status(405).json({
    error: "Method not allowed",
  });
};
