</main>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <span class="brand-icon">☪</span>
                <span class="brand-text">Persada <strong>Al-Amin</strong></span>
                <p>Sistem Pendukung Keputusan Pemilihan Paket Umrah terbaik sesuai kebutuhan Anda.</p>
            </div>
            <div class="footer-links">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>/">Beranda</a></li>
                    <li><a href="<?= BASE_URL ?>/paket">Paket Umrah</a></li>
                    <li><a href="<?= BASE_URL ?>/konsultasi/mulai">Konsultasi</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Kontak</h4>
                <p>📍 Jl. Contoh No. 123, Kota Anda</p>
                <p>📞 0800-XXX-XXXX</p>
                <p>✉ info@persadaalamin.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Persada Al-Amin. Sistem ini menggunakan metode <strong>Forward Chaining</strong>.</p>
        </div>
    </div>
</footer>

<script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
<?= $extraJs ?? '' ?>
<script>
document.getElementById('hamburger')?.addEventListener('click', function() {
    document.querySelector('.nav-links').classList.toggle('open');
});
</script>
</body>
</html>