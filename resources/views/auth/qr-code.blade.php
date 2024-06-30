<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .countdown-red {
        color: red;
    }
</style>
<!-- Start of Selection -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="modal-content">

            <div id="container" style="margin: auto; width: 100%; ">
                <br>
                <h1 style="text-align: center; font-size: 24px; font-family: 'Arial', sans-serif; margin-right: auto; margin-left: auto; font-weight: bold;">Presensi QR Code</h1>

                <img id="qrCodeImg" style="display: block; margin-left: auto; margin-right: auto;">

                <div id="countdown" style="text-align: center; font-size: 30px; font-family: 'Arial', sans-serif;"></div>
                <br>
                <div style="text-align: center; font-size: 16px; font-family: Arial; font-weight: bold;">Waktu Server: <span id="serverTime"></span></div>
                <script>
                    $(document).ready(function() {
                        // Fungsi untuk memperbarui waktu server setiap detik
                        function updateServerTime() {
                            var serverTime = new Date();
                            $('#serverTime').text(serverTime.toLocaleTimeString());
                        }

                        // Tampilkan waktu server saat ini
                        updateServerTime();

                        // Perbarui waktu server setiap detik
                        setInterval(updateServerTime, 1000);
                    });
                </script>
                <div id="serverTime" style="text-align: center; font-size: 20px; font-family: 'Arial', sans-serif; margin-top: 20px;"></div>

            </div>
            <script>
                function generateUniqueId(length) {
                    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    var charactersLength = characters.length;
                    var result = '';
                    for (var i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }
                    return result;
                }
                // Variable untuk menyimpan status hitungan mundur
                var countdownRunning = false;


                // Fungsi untuk memperbarui QR code setiap 30 detik
                function updateQRCode() {
                    // Generate ID acak baru untuk QR code
                    var id = generateUniqueId(20); // Misalnya menghasilkan ID sepanjang 20 karakter
                    console.log(id);
                    // Ubah sumber gambar QR code dengan ID baru
                    document.getElementById('qrCodeImg').src = 'https://quickchart.io/qr?text=' + id + '&dark=050109&ecLevel=H&margin=8&size=200&centerImageUrl=https%3A%2F%2Fpos.bael.my.id%2Fimg%2Fkasir.jpg';

                    // Set waktu akhir hitungan mundur di localStorage
                    localStorage.setItem('qrCodeExpiry', Date.now() + 30000);
                    localStorage.setItem('qrCodeId', id);

                    $.ajax({
                        url: '{{ route("generateQrCode") }}',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });


                }




                // Fungsi untuk menampilkan hitungan mundur
                function countdown() {
                    var countdownElement = document.getElementById('countdown');
                    var expiryTime = localStorage.getItem('qrCodeExpiry');
                    var seconds;

                    if (expiryTime) {
                        var currentTime = Date.now();
                        seconds = Math.floor((expiryTime - currentTime) / 1000);
                        if (seconds <= 0) {
                            updateQRCode();
                            seconds = 30;
                        }
                    } else {
                        seconds = 30;
                    }

                    // Fungsi rekursif untuk mengurangi waktu
                    function updateCountdown() {
                        countdownElement.textContent = seconds + ' detik';
                        if (seconds <= 10) {
                            countdownElement.classList.add('countdown-red');
                        } else {
                            countdownElement.classList.remove('countdown-red');
                        }
                        seconds--;

                        // Jika hitungan mundur mencapai 0, perbarui QR code dan mulai hitungan mundur lagi
                        if (seconds < 0) {
                            updateQRCode();
                            seconds = 30;
                            $('.btn-secondary').click(); // Otomatis klik tombol tutup setelah 30 detik
                        }

                        // Panggil fungsi rekursif setiap detik
                        setTimeout(updateCountdown, 1000);
                    }

                    // Mulai hitungan mundur jika belum berjalan
                    if (!countdownRunning) {
                        updateCountdown();
                        countdownRunning = true;
                    }
                }

                // Panggil fungsi countdown() saat halaman dimuat
                countdown();

                // Muat ulang ID QR code dari localStorage saat halaman dimuat
                window.onload = function() {
                    var id = localStorage.getItem('qrCodeId');
                    if (id) {
                        document.getElementById('qrCodeImg').src = 'https://quickchart.io/qr?text=' + id + '&dark=050109&ecLevel=H&margin=8&size=200&centerImageUrl=https%3A%2F%2Fpos.bael.my.id%2Fimg%2Fkasir.jpg';
                    } else {
                        updateQRCode();
                    }
                }
            </script>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #FF0000; color: white;">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Selection -->