@echo off
title Diabetes AI Analyzer - Host Online
echo ========================================================
echo   DIABETES AI ANALYZER - ONLINE HOSTING LAUNCHER
echo ========================================================
echo.
echo 1. Memulai server Laravel (Port 8000)...
start /B php artisan serve --host=127.0.0.1 --port=8000

echo 2. Memulai engine Machine Learning Streamlit (Port 8501)...
start /B "c:\laragon\www\TUGAS PAK ABU\.venv\Scripts\python.exe" -m streamlit run app.py --server.port 8501 --server.headless true

echo 3. Mengaktifkan Cloudflare Tunnel untuk akses publik HTTPS...
echo.
C:\laragon\bin\cloudflared.exe tunnel --url http://127.0.0.1:8000
pause
