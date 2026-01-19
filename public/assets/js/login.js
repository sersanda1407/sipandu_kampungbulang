// #Open Modal guys
function openModal() {
    document.getElementById("registerModal").style.display = "block";
}

function closeModal() {
    document.getElementById("registerModal").style.display = "none";
}

function openModalhubAdmin() {
    document.getElementById("hubAdminModal").style.display = "block";
    document.getElementById('contactAdminForm').reset();
}

function closeHubAdminModal() {
    document.getElementById("hubAdminModal").style.display = "none";
}

function showPrivacyPolicy() {
    document.getElementById('privacyModal').style.display = 'block';
}

function closePrivacyModal() {
    document.getElementById('privacyModal').style.display = 'none';
}

// #Pesan WA laporan
function sendWhatsAppMessage(event) {
    event.preventDefault();

    const noKK = document.getElementById('contact_no_kk').value.trim();
    const nama = document.getElementById('contact_nama').value.trim();
    const pesan = document.getElementById('contact_pesan').value.trim();

    if (noKK.length !== 16) {
        Swal.fire({
            icon: 'error',
            title: 'Nomor KK Tidak Valid',
            text: 'Nomor Kartu Keluarga harus terdiri dari 16 digit angka',
            timer: 5000
        });
        return;
    }

    if (!nama) {
        Swal.fire({
            icon: 'error',
            title: 'Nama Harus Diisi',
            text: 'Silakan masukkan nama lengkap Anda',
            timer: 5000
        });
        return;
    }

    const phoneNumber = '6287875538815';
    let message = `*Halo Admin SIPANDU Kampung Bulang*%0A%0A`;
    message += `*Data Pengirim:*%0A`;
    message += `*Nama:* ${nama}%0A`;
    message += `*No. KK:* ${noKK}%0A`;
    message += `*Tanggal:* ${new Date().toLocaleDateString('id-ID')}%0A`;

    if (pesan) {
        message += `*Pesan:*%0A${pesan}%0A%0A`;
    }

    const whatsappURL = `https://wa.me/${phoneNumber}?text=${message}`;

    Swal.fire({
        title: 'Konfirmasi Pengiriman',
        html: `Anda akan diarahkan ke WhatsApp untuk mengirim pesan ke Admin.<br>`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#4E71FF',
        confirmButtonText: 'Kirim Pesan',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(whatsappURL, '_blank');
            closeHubAdminModal();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Anda akan diarahkan ke WhatsApp',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
}

// #Cek Duplikasi No HP
function checkDuplicatePhone(phoneNumber) {
    return fetch('/api/check-phone?no_telp=' + encodeURIComponent(phoneNumber))
        .then(response => response.json())
        .then(data => data.exists);
}

// #Decode Data RT
function getRTData() {
    if (window.rtDataEncrypted) {
        try {
            const decoded = atob(window.rtDataEncrypted);
            return JSON.parse(decoded);
        } catch (error) {
            console.error('Error decoding RT data:', error);
            return [];
        }
    }
    return [];
}

// #Penyesuaian Tahun Data
document.addEventListener('DOMContentLoaded', function() {
    const yearElement = document.getElementById("year");
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
    
    initEventListeners();
    initFileUpload();
    initValidation();
    initPrivacyPolicy();
    initRTFilter();
});

// #Fungsi Modal (register, hubungi admin dan privacy policy)
function initEventListeners() {
    window.onclick = function (event) {
        const registerModal = document.getElementById("registerModal");
        if (event.target == registerModal) {
            registerModal.style.display = "none";
        }

        const hubAdminModal = document.getElementById("hubAdminModal");
        if (event.target == hubAdminModal) {
            hubAdminModal.style.display = "none";
        }

        const privacyModal = document.getElementById('privacyModal');
        if (event.target == privacyModal) {
            privacyModal.style.display = "none";
        }
    }

    const contactNoKKInput = document.getElementById('contact_no_kk');
    if (contactNoKKInput) {
        contactNoKKInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
}

// #Validasi File Upload-an (jenis file dan ukuran max)
function initFileUpload() {
    document.querySelectorAll('.upload-gambar').forEach(function (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) return;

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Hanya file gambar yang diperbolehkan! (jpg, jpeg, png)'
                });
                this.value = '';
                return;
            }

            const maxSize = 3 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Gambar Terlalu Besar!',
                    text: 'Ukuran file maksimal adalah 3 MB. File Anda: ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB'
                });
                this.value = '';
                return;
            }
        });
    });
}

// #Validasi Form (inputan, nomor telepon, no kk)
function initValidation() {
    const noHpInput = document.getElementById("no_hp");
    if (noHpInput) {
        noHpInput.addEventListener("input", function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        noHpInput.addEventListener('blur', function (e) {
            const phoneNumber = this.value.trim();

            if (phoneNumber.length >= 8) {
                checkDuplicatePhone(phoneNumber).then(isDuplicate => {
                    if (isDuplicate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nomor HP Sudah Terdaftar',
                            text: 'Nomor HP ini sudah terdaftar dalam sistem. Silakan gunakan nomor lain.',
                            timer: 5000
                        });

                        this.value = '';
                        this.focus();
                    }
                }).catch(error => {
                    console.error('Error checking phone:', error);
                });
            }
        });
    }

    const noKkInput = document.getElementById('no_kk');
    if (noKkInput) {
        noKkInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    const registrationForm = document.querySelector('form[action*="kk.storePublic"]');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function (e) {
            const phoneInput = document.getElementById('no_hp');
            if (!phoneInput) return;
            
            const phoneNumber = phoneInput.value.trim();

            if (phoneNumber.length >= 8) {
                e.preventDefault();

                checkDuplicatePhone(phoneNumber).then(isDuplicate => {
                    if (isDuplicate) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak Dapat Mendaftar',
                            text: 'Nomor HP ini sudah terdaftar dalam sistem. Silakan gunakan nomor lain.',
                            timer: 5000
                        });

                        phoneInput.value = '';
                        phoneInput.focus();
                    } else {
                        e.target.submit();
                    }
                }).catch(error => {
                    console.error('Error checking phone:', error);
                    e.target.submit();
                });
            }
        });
    }
}

// #Popup Kebijakan Privacy
function initPrivacyPolicy() {
    const privacyCheckbox = document.getElementById('privacyPolicy');
    const submitButton = document.getElementById('submitButton');
    const registrationForm = document.getElementById('registrationForm');

    if (!privacyCheckbox || !submitButton || !registrationForm) return;


    privacyCheckbox.addEventListener('change', function () {
        if (this.checked) {
            submitButton.disabled = false;
            submitButton.classList.remove('btn-disabled');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('btn-disabled');
        }
    });

    registrationForm.addEventListener('submit', function (e) {
        if (!privacyCheckbox.checked) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Persetujuan Diperlukan',
                text: 'Anda harus menyetujui Kebijakan Privasi sebelum mendaftar',
                timer: 5000
            });
        }
    });
}

// #Filter RT berdasarkan RW
function initRTFilter() {
    const rwSelect = document.getElementById('rw_id_modal');
    if (!rwSelect) return;
    
    rwSelect.addEventListener('change', function () {
        const rwId = this.value;
        const rtSelect = document.getElementById('rt_id_modal');
        if (!rtSelect) return;

        rtSelect.innerHTML = '<option value="">-- Pilih RT --</option>';

        const rtData = getRTData();

        if (rtData && Array.isArray(rtData)) {
            const filteredRt = rtData.filter(rt => rt.rw_id == rwId);
            filteredRt.forEach(rt => {
                const option = document.createElement('option');
                option.value = rt.id;
                option.textContent = `${rt.rt} | ${rt.nama}`;
                rtSelect.appendChild(option);
            });
        }
    });
}

window.openModal = openModal;
window.closeModal = closeModal;
window.openModalhubAdmin = openModalhubAdmin;
window.closeHubAdminModal = closeHubAdminModal;
window.showPrivacyPolicy = showPrivacyPolicy;
window.closePrivacyModal = closePrivacyModal;
window.sendWhatsAppMessage = sendWhatsAppMessage;