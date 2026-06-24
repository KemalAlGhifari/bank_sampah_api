<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kebijakan Privasi - Bank Sampah</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS Variables & Base Styles -->
    <style>
        :root {
            /* Theme Light */
            --bg-primary: #f3f7f5;
            --bg-secondary: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --accent: #10b981;
            --accent-soft: rgba(16, 185, 129, 0.1);
            --accent-hover: #059669;
            --border: #e2e8f0;
            --card-shadow: 0 10px 30px -10px rgba(16, 185, 129, 0.08), 0 1px 3px rgba(0, 0, 0, 0.02);
            --glow: 0 0 20px rgba(16, 185, 129, 0.15);
            --toc-active-bg: rgba(16, 185, 129, 0.08);
            --font-title: 'Outfit', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            /* Theme Dark */
            --bg-primary: #080f0c;
            --bg-secondary: #0f1a15;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent: #34d399;
            --accent-soft: rgba(52, 211, 153, 0.1);
            --accent-hover: #10b981;
            --border: #1b2e25;
            --card-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5), 0 1px 3px rgba(0, 0, 0, 0.1);
            --glow: 0 0 25px rgba(52, 211, 153, 0.15);
            --toc-active-bg: rgba(52, 211, 153, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: var(--font-body);
            line-height: 1.7;
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            transition: var(--transition);
        }

        [data-theme="dark"] .navbar {
            background-color: rgba(15, 26, 21, 0.8);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-primary);
            font-family: var(--font-title);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .brand-logo {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: white;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            box-shadow: var(--glow);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-icon {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .btn-icon:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .btn-back:hover {
            color: var(--accent);
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        /* Container Layout */
        .page-header {
            max-width: 1200px;
            width: 100%;
            margin: 3rem auto 1.5rem;
            padding: 0 2rem;
            text-align: center;
        }

        .page-title {
            font-family: var(--font-title);
            font-size: 2.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, var(--text-primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .last-updated {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent-soft);
            color: var(--accent);
            padding: 0.35rem 1rem;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .main-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto 5rem;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2.5rem;
        }

        /* Sidebar & TOC */
        .sidebar {
            position: sticky;
            top: 90px;
            height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .toc-card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .toc-title {
            font-family: var(--font-title);
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .toc-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .toc-item a {
            display: block;
            padding: 0.6rem 0.85rem;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .toc-item a:hover {
            color: var(--accent);
            background-color: var(--accent-soft);
        }

        .toc-item.active a {
            color: var(--accent);
            background-color: var(--toc-active-bg);
            border-left-color: var(--accent);
            font-weight: 600;
        }

        /* Search Bar styling */
        .search-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .search-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }

        .search-results-info {
            font-size: 0.8rem;
            color: var(--accent);
            margin-top: 0.4rem;
            display: none;
            font-weight: 500;
        }

        /* Content Area */
        .content-card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem 3rem;
            box-shadow: var(--card-shadow);
        }

        .policy-section {
            scroll-margin-top: 100px;
            margin-bottom: 3.5rem;
        }

        .policy-section:last-child {
            margin-bottom: 0;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 0.75rem;
        }

        .section-icon {
            color: var(--accent);
            background: var(--accent-soft);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .section-header h2 {
            font-family: var(--font-title);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .policy-section p {
            margin-bottom: 1rem;
            color: var(--text-secondary);
        }

        .policy-section ul, .policy-section ol {
            margin-left: 1.5rem;
            margin-bottom: 1.25rem;
            color: var(--text-secondary);
        }

        .policy-section li {
            margin-bottom: 0.5rem;
        }

        .policy-section strong {
            color: var(--text-primary);
        }

        /* Feature grid/cards for data collection */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin: 1.5rem 0;
        }

        .data-card {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            transition: var(--transition);
        }

        .data-card:hover {
            transform: translateY(-2px);
            border-color: var(--accent);
            box-shadow: var(--card-shadow);
        }

        .data-card-title {
            font-family: var(--font-title);
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .data-card-title svg {
            color: var(--accent);
        }

        .data-card p {
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        /* Highlight tag styling */
        mark.highlight {
            background-color: rgba(253, 224, 71, 0.4);
            color: inherit;
            border-radius: 2px;
            padding: 0 2px;
            border-bottom: 2px solid #eab308;
            font-weight: 600;
        }

        [data-theme="dark"] mark.highlight {
            background-color: rgba(234, 179, 8, 0.3);
            border-bottom: 2px solid #facc15;
        }

        /* Highlight pulse effect */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Callout Alert */
        .callout {
            background: var(--accent-soft);
            border-left: 4px solid var(--accent);
            border-radius: 8px;
            padding: 1.25rem;
            margin: 1.5rem 0;
        }

        .callout-title {
            font-family: var(--font-title);
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .callout p {
            font-size: 0.9rem;
            margin-bottom: 0;
            color: var(--text-secondary);
        }

        /* Footer */
        .footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            background-color: var(--bg-secondary);
            padding: 2rem;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: relative;
                top: 0;
                height: auto;
                order: -1;
            }

            .page-title {
                font-size: 2.25rem;
            }

            .content-card {
                padding: 2rem;
            }
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 1rem;
            }
            .page-header {
                margin: 2rem auto 1rem;
                padding: 0 1rem;
            }
            .main-container {
                padding: 0 1rem 3rem;
            }
            .page-title {
                font-size: 1.75rem;
            }
            .content-card {
                padding: 1.5rem;
            }
            .section-header h2 {
                font-size: 1.25rem;
            }
        }

        /* Print Media Styles */
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .navbar, .sidebar, .btn-icon, .btn-back, .footer, .search-container {
                display: none !important;
            }
            .main-container {
                grid-template-columns: 1fr !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .content-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                background: transparent !important;
            }
            .page-header {
                margin-top: 0 !important;
            }
            .page-title {
                background: none !important;
                -webkit-text-fill-color: initial !important;
                color: black !important;
                font-size: 24pt !important;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="brand">
                <div class="brand-logo">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg>
                </div>
                <span>Bank Sampah</span>
            </a>
            <div class="nav-actions">
                <a href="/" class="btn-back">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali</span>
                </a>
                <button id="theme-toggle" class="btn-icon" title="Ubah Tema" aria-label="Ubah Tema">
                    <!-- Sun / Moon Icon toggled by JS -->
                    <svg id="theme-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></svg>
                </button>
                <button onclick="window.print()" class="btn-icon" title="Cetak Kebijakan" aria-label="Cetak Kebijakan">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Page Header Hero -->
    <header class="page-header">
        <h1 class="page-title">Kebijakan Privasi</h1>
        <div class="last-updated">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>Pembaruan Terakhir: 24 Juni 2026</span>
        </div>
    </header>

    <!-- Main Content Layout -->
    <main class="main-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="search-container">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="search-input" class="search-input" placeholder="Cari kebijakan...">
                <div id="search-results" class="search-results-info">Menampilkan hasil...</div>
            </div>

            <div class="toc-card">
                <div class="toc-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>Daftar Isi</span>
                </div>
                <ul class="toc-list">
                    <li class="toc-item active"><a href="#pendahuluan">1. Pendahuluan</a></li>
                    <li class="toc-item"><a href="#data-dikumpulkan">2. Data yang Dikumpulkan</a></li>
                    <li class="toc-item"><a href="#penggunaan-data">3. Penggunaan Data</a></li>
                    <li class="toc-item"><a href="#penyimpanan-keamanan">4. Keamanan Data</a></li>
                    <li class="toc-item"><a href="#pihak-ketiga">5. Bagikan Data</a></li>
                    <li class="toc-item"><a href="#hak-nasabah">6. Hak-Hak Nasabah</a></li>
                    <li class="toc-item"><a href="#hubungi-kami">7. Hubungi Kami</a></li>
                </ul>
            </div>
        </aside>

        <!-- Privacy Content Section -->
        <article class="content-card" id="policy-content">
            
            <section id="pendahuluan" class="policy-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <h2>1. Pendahuluan</h2>
                </div>
                <p>Selamat datang di Aplikasi <strong>Bank Sampah</strong>. Kami sangat menghargai privasi dan kepercayaan Anda sebagai nasabah kami. Kebijakan Privasi ini dirancang untuk menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda saat Anda menggunakan aplikasi kami.</p>
                <p>Dengan mendaftar, mengakses, atau menggunakan layanan Bank Sampah kami, Anda menyetujui pengumpulan dan penggunaan informasi sesuai dengan ketentuan yang diatur dalam dokumen ini. Kebijakan ini berlaku untuk seluruh pengguna baik sebagai Nasabah, Petugas Lapangan, maupun Administrator sistem.</p>
            </section>

            <section id="data-dikumpulkan" class="policy-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <h2>2. Data yang Kami Kumpulkan</h2>
                </div>
                <p>Untuk menunjang kelancaran program tabungan dan penjemputan sampah, kami mengumpulkan beberapa data pribadi yang Anda berikan secara langsung saat menggunakan aplikasi:</p>

                <div class="data-grid">
                    <div class="data-card">
                        <div class="data-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            <span>Identitas Akun</span>
                        </div>
                        <p>Nama lengkap, alamat email, nomor telepon aktif, kata sandi terenkripsi, dan peran (role) pengguna dalam sistem.</p>
                    </div>
                    <div class="data-card">
                        <div class="data-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <span>Nomor NIK</span>
                        </div>
                        <p>Nomor Induk Kependudukan (NIK) digunakan secara khusus untuk memvalidasi pendaftaran nasabah baru guna menghindari akun ganda.</p>
                    </div>
                    <div class="data-card">
                        <div class="data-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span>Alamat & Rukun Tetangga</span>
                        </div>
                        <p>Alamat tempat tinggal lengkap beserta kode Rukun Tetangga (RT) untuk optimalisasi rute dan jadwal penjemputan sampah.</p>
                    </div>
                    <div class="data-card">
                        <div class="data-card-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            <span>Transaksi & Tabungan</span>
                        </div>
                        <p>Data setoran sampah (berat kg, jenis sampah), akumulasi total saldo (dalam Rupiah), riwayat penarikan (withdraw), dan status verifikasi.</p>
                    </div>
                </div>

                <div class="callout">
                    <div class="callout-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <span>Data FCM Token & Push Notifications</span>
                    </div>
                    <p>Aplikasi ini mengumpulkan data FCM (Firebase Cloud Messaging) token perangkat Anda. Token ini digunakan murni untuk mengirimkan notifikasi penting seperti konfirmasi setoran sampah, jadwal berjalan, serta status persetujuan atau penolakan penarikan saldo (withdraw).</p>
                </div>
            </section>

            <section id="penggunaan-data" class="policy-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <h2>3. Bagaimana Kami Menggunakan Informasi Anda</h2>
                </div>
                <p>Kami menggunakan data pribadi yang dikumpulkan untuk tujuan operasional dan peningkatan layanan:</p>
                <ul>
                    <li><strong>Proses Layanan Setoran</strong>: Menghitung estimasi harga sampah berdasarkan kategori dan berat, serta memperbarui total akumulasi kilogram sampah yang disetor.</li>
                    <li><strong>Manajemen Keuangan Nasabah</strong>: Mengelola saldo tabungan nasabah serta memproses permintaan penarikan dana (withdraw) secara transparan.</li>
                    <li><strong>Penjadwalan Rute</strong>: Mengoordinasikan jadwal penjemputan sampah rutin di area RT Anda agar proses pengangkutan lebih efisien.</li>
                    <li><strong>Notifikasi Real-time</strong>: Mengirimkan pembaruan status transaksi setoran maupun penarikan langsung ke ponsel Anda.</li>
                    <li><strong>Keamanan Sistem</strong>: Mencegah penyalahgunaan sistem seperti pendaftaran ganda menggunakan NIK yang tidak valid.</li>
                </ul>
            </section>

            <section id="penyimpanan-keamanan" class="policy-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <h2>4. Keamanan & Penyimpanan Data</h2>
                </div>
                <p>Keamanan informasi Anda adalah prioritas utama kami. Kami menerapkan langkah-langkah keamanan teknis berikut untuk menjaga data pribadi Anda:</p>
                <ol>
                    <li><strong>Enkripsi Sandi</strong>: Kata sandi Anda dilindungi dan disimpan menggunakan hash satu arah berstandar industri (Bcrypt).</li>
                    <li><strong>Autentikasi Teraman</strong>: Komunikasi API antara aplikasi seluler dan server backend diamankan menggunakan token autentikasi (Laravel Sanctum).</li>
                    <li><strong>Perlindungan Data Sensitif</strong>: NIK dan data transaksi keuangan Anda disimpan dalam basis data yang terlindung dan hanya dapat diakses oleh petugas resmi yang berwenang.</li>
                </ol>
                <p>Kami akan menyimpan data Anda selama akun Anda aktif di sistem kami atau sejauh yang diperlukan untuk mematuhi kewajiban pelaporan hukum.</p>
            </section>

            <section id="pihak-ketiga" class="policy-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </div>
                    <h2>5. Bagikan Data & Keterbukaan Pihak Ketiga</h2>
                </div>
                <p>Kami <strong>tidak akan menjual, memperdagangkan, atau menyewakan</strong> informasi pribadi nasabah kepada pihak ketiga.</p>
                <p>Data pribadi seperti Nama dan Alamat hanya akan dibagikan kepada koordinator lingkungan (Ketua RT atau Petugas Penjemput Sampah resmi) di wilayah Anda guna memfasilitasi proses penjemputan sampah fisik dan pengelolaan laporan rekapitulasi tingkat kelurahan/RT setempat.</p>
            </section>

            <section id="hak-nasabah" class="policy-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <h2>6. Hak-Hak Nasabah</h2>
                </div>
                <p>Sebagai pemilik data pribadi, Anda memiliki hak-hak berikut:</p>
                <ul>
                    <li><strong>Hak Akses</strong>: Anda berhak memeriksa saldo tabungan, riwayat setoran, dan riwayat penarikan Anda secara langsung melalui aplikasi kapan saja.</li>
                    <li><strong>Hak Koreksi</strong>: Anda dapat memperbarui informasi kontak Anda seperti nomor telepon dan email jika terjadi perubahan.</li>
                    <li><strong>Hak Penghapusan</strong>: Anda dapat mengajukan permohonan penonaktifan atau penghapusan akun beserta data terkait melalui Administrator Bank Sampah setempat.</li>
                </ul>
            </section>

            <section id="hubungi-kami" class="policy-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <h2>7. Hubungi Kami</h2>
                </div>
                <p>Jika Anda memiliki pertanyaan, saran, atau kekhawatiran terkait Kebijakan Privasi ini, silakan hubungi tim administrator kami melalui:</p>
                <p>
                    <strong>Email:</strong> support@banksampah.example.com<br>
                    <strong>Telepon / WA Koordinasi:</strong> +62 812-3456-7890<br>
                    <strong>Sekretariat:</strong> Kantor Bank Sampah Induk Wilayah, Indonesia
                </p>
            </section>

        </article>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Bank Sampah. Seluruh hak cipta dilindungi undang-undang.</p>
    </footer>

    <!-- Interactive Javascript Features -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Theme Management
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            
            // Check saved theme or user preference
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let currentTheme = savedTheme || (prefersDark ? 'dark' : 'light');
            
            // Set initial theme
            document.documentElement.setAttribute('data-theme', currentTheme);
            updateThemeIcon(currentTheme);
            
            themeToggleBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'light' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', currentTheme);
                localStorage.setItem('theme', currentTheme);
                updateThemeIcon(currentTheme);
            });
            
            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    // Sun icon for dark mode (click to go light)
                    themeIcon.innerHTML = `
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    `;
                } else {
                    // Moon icon for light mode (click to go dark)
                    themeIcon.innerHTML = `
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    `;
                }
            }

            // Scroll Spying Navigation
            const sections = document.querySelectorAll('.policy-section');
            const tocItems = document.querySelectorAll('.toc-item');
            
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -60% 0px',
                threshold: 0
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const activeId = entry.target.getAttribute('id');
                        tocItems.forEach(item => {
                            const link = item.querySelector('a');
                            if (link.getAttribute('href') === `#${activeId}`) {
                                item.classList.add('active');
                            } else {
                                item.classList.remove('active');
                            }
                        });
                    }
                });
            }, observerOptions);
            
            sections.forEach(section => observer.observe(section));

            // Dynamic Term Search Filtering & Highlighting
            const searchInput = document.getElementById('search-input');
            const contentArea = document.getElementById('policy-content');
            const resultsInfo = document.getElementById('search-results');
            
            // Backup the original content HTML to reset highlighting correctly
            const originalContentHTML = contentArea.innerHTML;

            searchInput.addEventListener('input', () => {
                const query = searchInput.value.trim().toLowerCase();
                
                if (query.length < 2) {
                    // Reset highlights if query is empty or too short
                    contentArea.innerHTML = originalContentHTML;
                    resultsInfo.style.display = 'none';
                    // Re-observe elements as HTML has been reset
                    document.querySelectorAll('.policy-section').forEach(s => observer.observe(s));
                    return;
                }

                // Restore original HTML first to remove old marks
                contentArea.innerHTML = originalContentHTML;

                // Simple text-node traversal highlighting
                let matchCount = 0;
                
                function highlightTextNodes(element) {
                    const children = Array.from(element.childNodes);
                    
                    for (let node of children) {
                        if (node.nodeType === 3) { // Text Node
                            const text = node.nodeValue;
                            const lowerText = text.toLowerCase();
                            
                            if (lowerText.includes(query)) {
                                const parent = node.parentNode;
                                const fragment = document.createDocumentFragment();
                                let lastIdx = 0;
                                let idx = lowerText.indexOf(query);
                                
                                while (idx !== -1) {
                                    // Add preceding text
                                    if (idx > lastIdx) {
                                        fragment.appendChild(document.createTextNode(text.substring(lastIdx, idx)));
                                    }
                                    
                                    // Create mark tag
                                    const mark = document.createElement('mark');
                                    mark.className = 'highlight';
                                    mark.textContent = text.substring(idx, idx + query.length);
                                    fragment.appendChild(mark);
                                    
                                    matchCount++;
                                    lastIdx = idx + query.length;
                                    idx = lowerText.indexOf(query, lastIdx);
                                }
                                
                                // Add remaining text
                                if (lastIdx < text.length) {
                                    fragment.appendChild(document.createTextNode(text.substring(lastIdx)));
                                }
                                
                                parent.replaceChild(fragment, node);
                            }
                        } else if (node.nodeType === 1 && node.nodeName !== 'SCRIPT' && node.nodeName !== 'STYLE') {
                            highlightTextNodes(node);
                        }
                    }
                }

                highlightTextNodes(contentArea);

                // Show matching result status
                resultsInfo.style.display = 'block';
                if (matchCount > 0) {
                    resultsInfo.textContent = `Menemukan ${matchCount} kecocokan`;
                    resultsInfo.style.color = 'var(--accent)';
                    
                    // Smoothly scroll to the first match
                    const firstMatch = contentArea.querySelector('mark.highlight');
                    if (firstMatch) {
                        firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    resultsInfo.textContent = 'Tidak ada hasil cocok';
                    resultsInfo.style.color = '#ef4444';
                }

                // Re-observe sections after DOM replacement
                document.querySelectorAll('.policy-section').forEach(s => observer.observe(s));
            });
        });
    </script>
</body>
</html>
