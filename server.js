const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const path = require('path');

const app = express();
const port = 8080;

const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '12345678',
  database: 'hotel_web'
});

db.connect((err) => {
  if (err) {
    console.error('Помилка підключення до бази даних:', err);
    return;
  }
  console.log('Підключено до MySQL бази даних');
});

app.use(cors());
app.use(express.json());

const staticPath = path.join(__dirname, '../src');
console.log(`Serving static files from ${staticPath}`);
app.use(express.static(staticPath));

app.get('/api/rooms', (req, res) => {
  const sql = 'SELECT * FROM rooms';
  db.query(sql, (err, results) => {
    if (err) {
      console.error('Помилка при отриманні кімнат:', err);
      res.status(500).json({ error: 'Не вдалося отримати кімнати' });
      return;
    }
    console.log('Отримані кімнати:', results);
    res.json(results);
  });
});

app.get('/test', (req, res) => {
  res.send('Сервер працює');
});

app.listen(port, () => {
  console.log(`Сервер працює на порті ${port}`);
});
