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
      const results = await mysql.query(
        "SELECT nama_jurusan, deskripsi, prospek_karir, jenis_sekolah FROM jurusan ORDER BY jenis_sekolah ASC, nama_jurusan ASC"
      );
      await mysql.end();

      // Format results to match PHP structure
      const formattedResults = results.map((jurusan) => ({
        nama_jurusan: jurusan.nama_jurusan,
        deskripsi: jurusan.deskripsi,
        prospek_karir: jurusan.prospek_karir,
        jenis_sekolah: jurusan.jenis_sekolah,
      }));

      return res.status(200).json(formattedResults);
    } catch (error) {
      console.error("Database error:", error);
      return res.status(500).json({
        error: "Terjadi kesalahan saat mengambil data jurusan",
      });
    }
  }

  // Handle unsupported methods
  return res.status(405).json({
    error: "Method not allowed",
  });
};
