const express = require('express');
const path = require('path');

const app = express();

// Налаштування шляху до статичних файлів
app.use(express.static(path.join(__dirname, 'public')));

// Запуск сервера на порті 8080
const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
