@echo off
title WPCL Cloudflare Online Tunnel
color 0b
echo ============================================================
echo           WPCL - CLOUDFLARE ONLINE TUNNEL (JALUR B)
echo ============================================================
echo.
echo Pastikan Laragon / MySQL dan server Laravel sudah menyala di port 8000!
echo.
echo Sedang menghubungkan laptop ke jaringan Cloudflare...
echo.
cd /d "%~dp0"
cloudflared.exe tunnel --url http://127.0.0.1:8000
pause
