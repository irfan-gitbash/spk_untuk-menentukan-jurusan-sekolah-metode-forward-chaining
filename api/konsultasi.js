const mysql = require("./db");

module.exports = async (req, res) => {
  // Enable CORS
  res.setHeader("Access-Control-Allow-Credentials", true);
  res.setHeader("Access-Control-Allow-Origin", "*");
  res.setHeader(
    "Access-Control-Allow-Methods",
    "GET,OPTIONS,PATCH,DELETE,POST,PUT"
  );
  res.setHeader(
    "Access-Control-Allow-Headers",
    "X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version"
  );

  // Handle OPTIONS request
  if (req.method === "OPTIONS") {
    res.status(200).end();
    return;
  }

  // Handle POST request
  if (req.method === "POST") {
    try {
      const { nama, nisn, kelas, sekolah } = req.body;

      // Validate required fields
      if (!nama || !nisn || !kelas || !sekolah) {
        return res.status(400).json({
          error: "Semua field harus diisi",
        });
      }

      // Insert data into database
      const result = await mysql.query(
        "INSERT INTO konsultasi (nama, nisn, kelas, sekolah, tanggal) VALUES (?, ?, ?, ?, NOW())",
        [nama, nisn, kelas, sekolah]
      );

      await mysql.end();

      // Return success response
      return res.status(200).json({
        message: "Data berhasil disimpan",
        id: result.insertId,
      });
    } catch (error) {
      console.error("Database error:", error);
      return res.status(500).json({
        error: "Terjadi kesalahan saat menyimpan data",
      });
    }
  }

  // Handle unsupported methods
  return res.status(405).json({
    error: "Method not allowed",
  });
};
