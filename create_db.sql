-- Buat database dan tabel untuk aplikasi kasir cafe
CREATE DATABASE IF NOT EXISTS cafe_kasir CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE cafe_kasir;

-- Tabel admin
CREATE TABLE IF NOT EXISTS admin (
  id_admin INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Tabel menu
CREATE TABLE IF NOT EXISTS menu (
  id_menu INT AUTO_INCREMENT PRIMARY KEY,
  nama_menu VARCHAR(255) NOT NULL,
  harga INT NOT NULL,
  gambar VARCHAR(255) DEFAULT NULL
);

-- Tabel transaksi
CREATE TABLE IF NOT EXISTS transaksi (
  id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATETIME NOT NULL,
  nama_pelanggan VARCHAR(255) NOT NULL,
  total_harga INT NOT NULL
);

-- Tabel detail_transaksi
CREATE TABLE IF NOT EXISTS detail_transaksi (
  id_detail INT AUTO_INCREMENT PRIMARY KEY,
  id_transaksi INT NOT NULL,
  nama_menu VARCHAR(255) NOT NULL,
  jumlah INT NOT NULL,
  subtotal INT NOT NULL,
  FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE
);

-- Insert 30 menu dummy (menggunakan placeholder image URLs)
INSERT INTO menu (nama_menu, harga, gambar) VALUES
('Espresso', 12000, 'https://via.placeholder.com/400x300?text=Espresso'),
('Americano', 15000, 'https://via.placeholder.com/400x300?text=Americano'),
('Cappuccino', 20000, 'https://via.placeholder.com/400x300?text=Cappuccino'),
('Latte', 20000, 'https://via.placeholder.com/400x300?text=Latte'),
('Mocha', 22000, 'https://via.placeholder.com/400x300?text=Mocha'),
('Flat White', 21000, 'https://via.placeholder.com/400x300?text=Flat+White'),
('Affogato', 25000, 'https://via.placeholder.com/400x300?text=Affogato'),
('Iced Coffee', 18000, 'https://via.placeholder.com/400x300?text=Iced+Coffee'),
('Iced Latte', 23000, 'https://via.placeholder.com/400x300?text=Iced+Latte'),
('Iced Mocha', 24000, 'https://via.placeholder.com/400x300?text=Iced+Mocha'),
('Chocolate', 17000, 'https://via.placeholder.com/400x300?text=Chocolate'),
('Matcha Latte', 25000, 'https://via.placeholder.com/400x300?text=Matcha+Latte'),
('Teh Tarik', 14000, 'https://via.placeholder.com/400x300?text=Teh+Tarik'),
('Green Tea', 16000, 'https://via.placeholder.com/400x300?text=Green+Tea'),
('Lemon Tea', 15000, 'https://via.placeholder.com/400x300?text=Lemon+Tea'),
('Milkshake Coklat', 28000, 'https://via.placeholder.com/400x300?text=Milkshake+Coklat'),
('Milkshake Vanilla', 28000, 'https://via.placeholder.com/400x300?text=Milkshake+Vanilla'),
('Jus Jeruk', 18000, 'https://via.placeholder.com/400x300?text=Jus+Jeruk'),
('Jus Mangga', 20000, 'https://via.placeholder.com/400x300?text=Jus+Mangga'),
('Jus Strawberry', 21000, 'https://via.placeholder.com/400x300?text=Jus+Strawberry'),
('Roti Bakar Coklat', 15000, 'https://via.placeholder.com/400x300?text=Roti+Bakar+Coklat'),
('Roti Bakar Keju', 16000, 'https://via.placeholder.com/400x300?text=Roti+Bakar+Keju'),
('Pancake', 30000, 'https://via.placeholder.com/400x300?text=Pancake'),
('Waffle', 32000, 'https://via.placeholder.com/400x300?text=Waffle'),
('Salad Buah', 22000, 'https://via.placeholder.com/400x300?text=Salad+Buah'),
('Nasi Goreng', 30000, 'https://via.placeholder.com/400x300?text=Nasi+Goreng'),
('Mie Goreng', 28000, 'https://via.placeholder.com/400x300?text=Mie+Goreng'),
('Chicken Wrap', 35000, 'https://via.placeholder.com/400x300?text=Chicken+Wrap'),
('Fish & Chips', 45000, 'https://via.placeholder.com/400x300?text=Fish+%26+Chips'),
('Sate Ayam', 33000, 'https://via.placeholder.com/400x300?text=Sate+Ayam');
