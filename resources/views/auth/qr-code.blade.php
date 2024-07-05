<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
<style>
    .countdown-red {
        color: red;
    }

    #qrCodeImg {
        margin-top: 10px;
        margin-left: 25%;
    }
</style>
<div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="modal-content">
            <div id="container" style="margin: auto; width: 100%;">
                <br>
                <h1 style="text-align: center; font-size: 24px; font-family: 'Arial', sans-serif; margin-right: auto; margin-left: auto; font-weight: bold;">Presensi QR Code</h1>
                <div id="qrCodeImg"></div>
                <div id="countdown" style="text-align: center; font-size: 30px; font-family: 'Arial', sans-serif;"></div>
                <br>
                <div style="text-align: center; font-size: 16px; font-family: Arial; font-weight: bold;">Waktu: <span id="serverTime"></span></div>
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
                var countdownRunning = false;
                var countdownInterval;
                var countdownTimeout;

                function generateUniqueId(length) {
                    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    var charactersLength = characters.length;
                    var result = '';
                    for (var i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }
                    return result;
                }

                function updateQRCode() {
                    var id = generateUniqueId(20); // Misalnya menghasilkan ID sepanjang 20 karakter

                    QRCode.toDataURL(id, {
                        color: {
                            dark: '#050109', // Warna QR code
                            light: '#FFFFFF' // Warna background
                        },
                        width: 300,
                    }, function(err, url) {
                        if (err) console.error(err);
                        document.getElementById('qrCodeImg').innerHTML = '<img src="' + url + '" alt="QR Code">';
                    });

                    localStorage.setItem('qrCodeExpiry', Date.now() + 30000);
                    localStorage.setItem('qrCodeId', id);

                    $.ajax({
                        url: '{{ route("generateQrCode") }}',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                    });
                }

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

                    function updateCountdown() {
                        countdownElement.textContent = seconds + ' detik';
                        if (seconds <= 10) {
                            countdownElement.classList.add('countdown-red');
                        } else {
                            countdownElement.classList.remove('countdown-red');
                        }
                        seconds--;

                        if (seconds < 0) {
                            updateQRCode();
                            seconds = 30;
                        }

                        countdownTimeout = setTimeout(updateCountdown, 1000);
                    }

                    if (!countdownRunning) {
                        updateCountdown();
                        countdownRunning = true;
                    }
                }

                countdown();

                window.onload = function() {
                    var id = localStorage.getItem('qrCodeId');
                    if (id) {
                        QRCode.toDataURL(id, {
                            color: {
                                dark: '#050109', // Warna QR code
                                light: '#FFFFFF' // Warna background
                            },
                            width: 300,
                        }, function(err, url) {
                            if (err) console.error(err);
                            document.getElementById('qrCodeImg').innerHTML = '<img src="' + url + '" alt="QR Code">';
                        });
                    } else {
                        updateQRCode();
                    }
                }

                // Event handler untuk tombol "Tutup"
                $('#tutup').on('hide.bs.modal', function() {
                    clearTimeout(countdownTimeout);
                    countdownRunning = false;
                });
            </script>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" name="tutup" style="background-color: #FF0000; color: white;">Tutup</button>
            </div>
        </div>
    </div>
</div>