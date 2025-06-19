// Universal Receipt Generator
// File: public/js/receipt-generator.js

window.receiptGenerator = {
    // Data yang akan diisi dari setiap halaman
    data: {
        kode: '',
        tanggal: '',
        device: '',
        kategori: '',
        masalah: '',
        tindakan: '',
        harga: '',
        garansi: '',
        pelanggan: '',
        nomor_telp: '',
        email: '',
        teknisi: '',
        status: ''
    },

    // Set data transaksi
    setData: function(data) {
        this.data = { ...this.data, ...data };
    },

    // Generate receipt HTML
    generateReceipt: function() {
        const currentDate = new Date();
        const dateStr = currentDate.toLocaleDateString('id-ID');
        const timeStr = currentDate.toLocaleTimeString('id-ID');

        return `
        <div class="receipt-wrapper">
            <!-- Header -->
            <div class="receipt-header">
                <div class="receipt-logo">MG</div>
                <div class="receipt-company-name">MG TECH</div>
                <div class="receipt-company-info">
                    Jl. Sumpah Pemuda<br>
                    Palembang, Sumatera Selatan<br>
                    WhatsApp: +6282177775051
                </div>
            </div>

            <!-- Title -->
            <div class="receipt-title">STRUK PERBAIKAN</div>

            <!-- DateTime -->
            <div class="receipt-datetime">
                Dicetak: ${dateStr} ${timeStr}
            </div>

            <!-- Info Perbaikan -->
            <div class="receipt-section">
                <div class="receipt-section-title">DETAIL PERBAIKAN</div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Kode:</span>
                    <span class="receipt-info-value">${this.data.kode}</span>
                </div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Tanggal:</span>
                    <span class="receipt-info-value">${this.data.tanggal}</span>
                </div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Device:</span>
                    <span class="receipt-info-value">${this.data.device}</span>
                </div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Kategori:</span>
                    <span class="receipt-info-value">${this.data.kategori}</span>
                </div>
            </div>

            <div class="receipt-separator"></div>

            <!-- Masalah & Tindakan -->
            <div class="receipt-section">
                <div class="receipt-section-title">PERBAIKAN</div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Masalah:</span>
                </div>
                <div style="margin-bottom: 8px; font-size: 10px; padding-left: 10px;">
                    ${this.data.masalah}
                </div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Tindakan:</span>
                </div>
                <div style="margin-bottom: 8px; font-size: 10px; padding-left: 10px;">
                    ${this.data.tindakan}
                </div>
            </div>

            <div class="receipt-separator"></div>

            <!-- Pelanggan -->
            <div class="receipt-section">
                <div class="receipt-section-title">PELANGGAN</div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Nama:</span>
                    <span class="receipt-info-value">${this.data.pelanggan}</span>
                </div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Telepon:</span>
                    <span class="receipt-info-value">${this.data.nomor_telp}</span>
                </div>
                ${this.data.email && this.data.email !== '-' ? `
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Email:</span>
                    <span class="receipt-info-value">${this.data.email}</span>
                </div>
                ` : ''}
            </div>

            <div class="receipt-separator"></div>

            <!-- Teknisi -->
            <div class="receipt-section">
                <div class="receipt-section-title">TEKNISI</div>
                <div class="receipt-info-line">
                    <span class="receipt-info-label">Nama:</span>
                    <span class="receipt-info-value">${this.data.teknisi}</span>
                </div>
            </div>

            <!-- Garansi -->
            ${this.data.garansi && this.data.garansi !== 'Tidak ada' ? `
            <div class="receipt-garansi">
                <div class="receipt-garansi-title">GARANSI</div>
                <div class="receipt-garansi-item">${this.data.garansi}</div>
            </div>
            ` : ''}

            <!-- Total -->
            <div class="receipt-total">
                <div class="receipt-total-label">TOTAL BIAYA</div>
                <div class="receipt-total-amount">${this.data.harga}</div>
            </div>

            <!-- Status -->
            <div class="receipt-status">
                <div class="receipt-status-label">STATUS PERBAIKAN</div>
                <div class="receipt-status-badge receipt-status-${this.data.status.toLowerCase()}">
                    ${this.data.status.toUpperCase()}
                </div>
            </div>

            <!-- Footer -->
            <div class="receipt-footer">
                <div class="receipt-footer-line">Terima kasih atas kepercayaan Anda</div>
                <div class="receipt-footer-line">Simpan struk ini sebagai bukti</div>
                <div class="receipt-footer-line">www.mgtech.com</div>
            </div>
        </div>
        `;
    },

    // Inject receipt into page for printing
    injectForPrint: function() {
        // Remove existing container
        const existingContainer = document.getElementById('receipt-print-container');
        if (existingContainer) {
            existingContainer.remove();
        }

        // Create new container
        const receiptContainer = document.createElement('div');
        receiptContainer.id = 'receipt-print-container';
        receiptContainer.innerHTML = this.generateReceipt();

        // Append to body
        document.body.appendChild(receiptContainer);
    }
};

// Auto-setup print events
window.addEventListener('beforeprint', function() {
    if (window.receiptGenerator && window.receiptGenerator.data.kode) {
        window.receiptGenerator.injectForPrint();
    }
});

// Clean up after print
window.addEventListener('afterprint', function() {
    const container = document.getElementById('receipt-print-container');
    if (container) {
        container.remove();
    }
});
