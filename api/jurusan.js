const mysql = require("./db");

module.exports = async (req, res) => {
  try {
    const results = await mysql.query("SELECT * FROM jurusan");
    await mysql.end();
    res.json(results);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};
