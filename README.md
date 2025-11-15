# 🎧 **MoodWave – Emotion-Based Music Recommender**

MoodWave is an emotion-driven music recommendation system built using **HTML, CSS, JavaScript, PHP, and MySQL (XAMPP)**.
Users select a mood or authenticate through supported methods, and the system recommends music that matches their emotional state.

---

## 🚀 **Features**

### 😊 Emotion-Based Recommendation

* Users choose their mood or input emotional data
* System maps the mood to curated music suggestions
* Lightweight, fast, and responsive UI

### 🔐 Authentication System

* Login or access control using `auth.php`
* `callback.php` handles user redirects or OAuth-based flows
* Secure session handling using PHP

### 🛠️ Backend & API

* `api.php` provides mood → music response
* `config.php` stores system configurations
* `db.php` handles all database operations

---

## 🛠️ **Tech Stack**

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL
* **Server:** XAMPP
* **API Layer:** Custom PHP-based endpoints

---

## 📂 **Project Structure**

```
/api.php        → API endpoint for mood-based music
/auth.php       → Authentication handler
/callback.php   → OAuth/redirect handler
/config.php     → System configuration settings
/db.php         → Database connection file
/index.html     → Main UI for MoodWave
/assets/        → Images, icons, styles (if present)
```

*(Tell me if you want this auto-generated from your repo.)*

---

## ⚙️ **Installation & Setup (XAMPP)**

1. Copy the project folder to:

   ```
   C:/xampp/htdocs/
   ```
2. Start **Apache** and **MySQL** in XAMPP.
3. Create a new MySQL database in **phpMyAdmin**.
4. Import the provided `.sql` file (if included).
5. Update database credentials inside:

   ```
   db.php
   config.php
   ```
6. Run the project:

   ```
   http://localhost/MoodWave/
   ```

---

## 🎯 **Use Cases**

* Mood-based playlist suggestion
* Lightweight web recommendation engine
* Music therapy or emotion analysis projects
* Personal music enhancer

---

## 📄 **License**

This project is licensed under the **MIT License**.

