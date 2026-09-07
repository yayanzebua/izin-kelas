# Class Permission & Leave Management App 🎓

A comprehensive, web-based digital permission and leave management system designed to streamline request workflows and administrative approvals for university class cohorts. 

Built with scalability and user experience in mind, this application replaces inefficient manual administration with automated workflows, real-time status tracking, and instant notifications.

## 🚀 Key Features

* **Multi-Tier User Roles:** Distinct dashboards and access privileges for Students (Mahasiswa), Lecturers (Dosen), and Administrators.
* **Interactive Dashboard:** Equipped with an onboarding system (powered by Alpine.js) for new users to navigate the platform easily.
* **Automated Workflows:** Seamless leave request submission, review, and approval process.
* **Telegram Bot Integration:** Real-time push notifications for request status updates and administrative alerts via `TelegramService`.
* **Secure Authentication:** Robust session management and CSRF protection handled by Laravel's core security features.

## 🛠️ Tech Stack

* **Backend:** PHP 8.x, Laravel Framework
* **Frontend:** HTML5, CSS3, JavaScript (Alpine.js for reactive components)
* **Database:** MySQL
* **Integrations:** Telegram Bot API (RESTful API)
* **Architecture:** MVC (Model-View-Controller)

## 📋 Prerequisites

Before you begin, ensure you have met the following requirements:
* PHP >= 8.1
* Composer
* Node.js & NPM
* MySQL / MariaDB
* Git

## ⚙️ Installation & Setup Guide

Follow these steps to run the project locally:

**1. Clone the repository**
```bash
git clone [https://github.com/yayanzebua/izin-kelas.git](https://github.com/yayanzebua/izin-kelas.git)
cd izin-kelas