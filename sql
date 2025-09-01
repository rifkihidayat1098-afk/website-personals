CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'kepala_sekolah', 'siswa') NOT NULL
);


CREATE TABLE divisi (
    id_divisi INT AUTO_INCREMENT PRIMARY KEY,
    nama_divisi VARCHAR(100) NOT NULL
);

CREATE TABLE pertanyaan_divisi (
    id_pertanyaan INT AUTO_INCREMENT PRIMARY KEY,
    id_divisi INT NOT NULL,
    judul_pertanyaan VARCHAR(255) NOT NULL,
    isi_pertanyaan TEXT NOT NULL,
    tipe_pertanyaan ENUM('text', 'textarea', 'radio', 'checkbox', 'select') NOT NULL,
    is_required BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE opsi_pertanyaan (
    id_opsi INT AUTO_INCREMENT PRIMARY KEY,
    id_pertanyaan INT NOT NULL,
    opsi_text VARCHAR(255) NOT NULL,
    nilai_opsional VARCHAR(255),
    urutan INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pertanyaan) REFERENCES pertanyaan_divisi(id_pertanyaan)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE TABLE pendaftaran_siswa (
    id_registration INT AUTO_INCREMENT PRIMARY KEY,
    code_registration VARCHAR(50) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    place_birthdate VARCHAR(50) NOT NULL,
    birthdate DATE NOT NULL,address VARCHAR(255) NOT NULL,
    numphone VARCHAR(20) NOT NULL,
    father_name VARCHAR(100) NOT NULL,
    mother_name VARCHAR(100) NOT NULL,
    wali_name VARCHAR(100) NOT NULL,
    nik CHAR(16) NOT NULL,
    nisn CHAR(10) NOT NULL,
    golongan_darah ENUM('O', 'A', 'B', 'AB') NOT NULL,
    asal_school VARCHAR(100) NOT NULL,
    ijazah_number VARCHAR(45) NOT NULL,
    pas_photo VARCHAR(255) NOT NULL,
    ijasah_document VARCHAR(255) NOT NULL,
    doc_tambahan VARCHAR(255),
    sertifikat_divisi VARCHAR(255) DEFAULT NULL,status ENUM('pending', 'diterima', 'ditolak') NOT NULL DEFAULT 'pending',
    tanggal_daftar DATE NOT NULL DEFAULT CURRENT_DATE,
    casis_id_registration INT NOT NULL,
    id_divisi INT,
    FOREIGN KEY (casis_id_registration) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE jawaban_pertanyaan (
    id_jawaban INT AUTO_INCREMENT PRIMARY KEY,
    id_registration INT NOT NULL,
    id_pertanyaan INT NOT NULL,
    jawaban TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_registration) REFERENCES pendaftaran_siswa(id_registration)
        ON DELETE CASCADE,
    FOREIGN KEY (id_pertanyaan) REFERENCES pertanyaan_divisi(id_pertanyaan)
        ON DELETE CASCADE
);

CREATE TABLE jawaban_pertanyaan_opsi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_jawaban INT NOT NULL,
    id_opsi INT NOT NULL,
    FOREIGN KEY (id_jawaban) REFERENCES jawaban_pertanyaan(id_jawaban)
        ON DELETE CASCADE,
    FOREIGN KEY (id_opsi) RE5i54FERENCES opsi_pertanyaan(id_opsi)
        ON DELETE CASCADE
);




CREATE TABLE news (
  id_news INT(11) AUTO_INCREMENT PRIMARY KEY,
  news_title VARCHAR(130),
  news_content TEXT,
  news_datestamp DATE DEFAULT CURRENT_DATE
);

CREATE TABLE event (
    id_event INT(11) NOT NULL AUTO_INCREMENT,
    name_event VARCHAR(45) NOT NULL,
    date_event DATE DEFAULT CURRENT_DATE,
    detail_event TEXT,
    img_event VARCHAR(100),
    PRIMARY KEY (id_event)
);

CREATE TABLE notifications (
    id_notif INT(11) AUTO_INCREMENT PRIMARY KEY,
    casis_id_notif INT(11) NOT NULL,
    reg_id_notif VARCHAR(100) NOT NULL,
    receiver_id INT(11) NOT NULL,
    title_notif VARCHAR(200) NOT NULL,
    message_notif TEXT NOT NULL,
    read_notif INT(11) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notifications_sender FOREIGN KEY (casis_id_notif)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_notifications_receiver FOREIGN KEY (receiver_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
