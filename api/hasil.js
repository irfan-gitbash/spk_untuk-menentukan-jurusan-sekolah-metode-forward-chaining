const mysql = require("./db");

module.exports = async (req, res) => {
  // Enable CORS
  res.setHeader("Access-Control-Allow-Credentials", true);
  res.setHeader("Access-Control-Allow-Origin", "*");
  res.setHeader("Access-Control-Allow-Methods", "GET,DELETE,OPTIONS");
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
      let query = `
                SELECT 
                    k.id_siswa,
                    k.nama_lengkap,
                    k.nisn,
                    k.sekolah,
                    k.kelas,
                    k.tanggal,
                    j.nama_jurusan,
                    j.jenis_sekolah
                FROM konsultasi k
                JOIN jurusan j ON k.id_jurusan = j.id_jurusan
                ORDER BY k.tanggal DESC
            `;

      const results = await mysql.query(query);
      await mysql.end();

      return res.status(200).json(results);
    } catch (error) {
      console.error("Database error:", error);
      return res.status(500).json({
        error: "Terjadi kesalahan saat mengambil data hasil konsultasi",
      });
    }
  }

  // Handle DELETE request
  if (req.method === "DELETE") {
    try {
      const id = req.query.id || req.url.split("/").pop();

      if (!id) {
        return res.status(400).json({
          error: "ID siswa tidak ditemukan",
        });
      }

      await mysql.query("DELETE FROM konsultasi WHERE id_siswa = ?", [id]);
      await mysql.end();

      return res.status(200).json({
        message: "Data berhasil dihapus",
      });
    } catch (error) {
      console.error("Database error:", error);
      return res.status(500).json({
        error: "Terjadi kesalahan saat menghapus data",
      });
    }
  }

  // Handle unsupported methods
  return res.status(405).json({
    error: "Method not allowed",
  });
};
