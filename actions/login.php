<?php
include ("../includes/a_config.php");
include ("../includes/db_connection.php");
include "../lib/phpPasswordHashingLib-master/passwordLib.php";
session_start();

// Koneksi ke database sm_db
$conn = new mysqli($servername, $username, $password, "sm_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Resend OTP Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resendOtp'])) {
    $username = $_SESSION['username'];
    
    // Ambil nomor telepon dari tabel
    $hp_stmt = $conn2->prepare("SELECT no_hp FROM hp WHERE npk = ?");
    $hp_stmt->bind_param("s", $username);
    $hp_stmt->execute();
    $hp_stmt->bind_result($no_hp);
    $hp_stmt->fetch();
    $hp_stmt->close();

    if ($no_hp) {
        // Generate new OTP
        $otp = rand(100000, 999999);
        
        // Update OTP in database
        $update_stmt = $conn->prepare("UPDATE otp SET no_otp = ? WHERE npk = ?");
        $update_stmt->bind_param("ss", $otp, $username);
        $update_stmt->execute();
        $update_stmt->close();

        // Kirim OTP via SMS (implementasi SMS gateway disini)
        // Contoh: sendSMS($no_hp, "Kode OTP baru Anda: $otp");
        
        echo "OTP_Terkirim";
        exit();
    }
    exit();
}

// Handle Login Request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = '';
    if (empty($_POST['captcha'])) {
        echo "<script>alert('Captcha tidak boleh kosong. Silakan coba lagi!')</script>";
        echo "<script>window.location.replace('../login_page.php');</script>";
        exit();
    }
    if (strcasecmp($_SESSION['captcha'], $_POST['captcha']) != 0) {
        echo "<script>alert('Captcha salah. Silakan coba lagi!')</script>";
        echo "<script>window.location.replace('../login_page.php');</script>";
        exit();
    }

    $username = mysqli_real_escape_string($conn1, $_POST['npk']);
    $password = $_POST['pwd']; // Input password

    // Verifikasi username, password, dan departemen dari tabel ct_users_hash
    $stmt = $conn1->prepare("SELECT npk, full_name, pwd, dept, sect, golongan, acting FROM ct_users_hash WHERE npk = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn1->errno . ") " . $conn1->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($res_username, $res_full_name, $hashed_password, $res_dept, $res_sect, $res_golongan, $res_acting);
    $stmt->fetch();
    $stmt->close();

    if ($res_username && password_verify($password, $hashed_password)) {
        // Periksa apakah departemen adalah PROD, atau QA
        $allowed_departments = ['PROD', 'APROD', 'QA', 'HRD'];
        if (in_array($res_dept, $allowed_departments)) {

            // Cek role di tabel 'role' pada database sm_db
            $role_stmt = $conn->prepare("SELECT role FROM role WHERE npk = ?");
            $role_stmt->bind_param("s", $username);
            $role_stmt->execute();
            $role_stmt->bind_result($user_role);
            $role_stmt->fetch();
            $role_stmt->close();

            if ($user_role) {
                // Menyimpan data ke session
                $_SESSION['username'] = $res_username;
                $_SESSION['full_name'] = $res_full_name;
                $_SESSION['dept'] = $res_dept;
                $_SESSION['golongan'] = $res_golongan;
                $_SESSION['role'] = $user_role; // Menambahkan role ke session

                // Logic to set role based on golongan and dept
                if ($res_golongan >= 3) {
                    if ($res_dept == 'PROD') {
                        $_SESSION['dept'] = 'PROD';
                    } elseif ($res_dept == 'QA') {
                        $_SESSION['dept'] = 'QA';
                    } elseif ($res_dept == 'HRD') {
                        $_SESSION['dept'] = 'HRD';
                    } elseif ($res_dept == 'APROD') {
                        $_SESSION['dept'] = 'APROD';
                    }
                }

                // Ambil nomor telepon dari tabel hp
                $hp_stmt = $conn2->prepare("SELECT no_hp FROM hp WHERE npk = ?");
                $hp_stmt->bind_param("s", $username);
                $hp_stmt->execute();
                $hp_stmt->bind_result($no_hp);
                $hp_stmt->fetch();
                $hp_stmt->close();

                if ($no_hp) {
                  // Generate OTP
                  $otp = rand(100000, 999999);
                  $expired_date = date('Y-m-d H:i:s', strtotime('+5 minutes')); // OTP berlaku 5 menit
                  $send_date = date('Y-m-d H:i:s'); // Waktu OTP dikirim
                  $send_status = 1; // Status pengiriman OTP (1 = terkirim, 0 = gagal)
              
                  // Simpan OTP ke tabel otp
                  $otp_stmt = $conn->prepare("SELECT COUNT(*) FROM otp WHERE npk = ?");
                  $otp_stmt->bind_param("s", $username);
                  $otp_stmt->execute();
                  $otp_stmt->bind_result($count);
                  $otp_stmt->fetch();
                  $otp_stmt->close();
              
                  if ($count > 0) {
                      // Jika npk sudah ada, update OTP
                      $update_stmt = $conn->prepare("UPDATE otp SET no_otp = ?, exp_date = ?, send = ?, send_date = ? WHERE npk = ?");
                      $update_stmt->bind_param("ssiss", $otp, $expired_date, $send_status, $send_date, $username);
                      $update_stmt->execute();
                      $update_stmt->close();
                  } else {
                      // Jika npk belum ada, insert OTP baru
                      $insert_stmt = $conn->prepare("INSERT INTO otp (npk, no_otp, exp_date, send, send_date) VALUES (?, ?, ?, ?, ?)");
                      $insert_stmt->bind_param("sssis", $username, $otp, $expired_date, $send_status, $send_date);
                      $insert_stmt->execute();
                      $insert_stmt->close();
                  }
              
                  // Kirim OTP ke nomor telepon (gunakan API SMS gateway)
                  // Contoh: sendSMS($no_hp, "Kode OTP Anda adalah: $otp");
              
                  // Tampilkan modal OTP
                  echo "<script>
                  document.addEventListener('DOMContentLoaded', function() {
                      document.getElementById('otpModal').style.display = 'block';
                  });
                  </script>";
                } else {
                    echo "<script>alert('Nomor telepon tidak ditemukan.')</script>";
                    echo "<script>window.location.replace('../login_page.php');</script>";
                }
            } else {
                echo "<script>alert('Role tidak ditemukan.')</script>";
                echo "<script>window.location.replace('../login_page.php');</script>";
            }
        } else {
            echo "<script>alert('Hanya pengguna dengan departemen APROD, PROD, atau QA yang dapat login.')</script>";
            echo "<script>window.location.replace('../login_page.php');</script>";
        }
    } else {
        echo "<script>alert('User atau password Anda salah. Silakan coba lagi!')</script>";
        echo "<script>window.location.replace('../login_page.php');</script>";
    }
}
?>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 JS & Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal OTP -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="otpModalLabel">Masukkan OTP</h5>
        <button type="button" class="btn-close" id="closeModalBtn" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Silakan masukkan kode OTP yang dikirim ke Anda.</p>
        <p id="timer" class="text-danger fw-bold"></p>
        <form id="otpForm" action="verify_otp.php" method="POST">
          <div class="d-flex justify-content-center gap-2">
            <input type="text" class="otp-input form-control text-center" maxlength="1" required>
            <input type="text" class="otp-input form-control text-center" maxlength="1" required>
            <input type="text" class="otp-input form-control text-center" maxlength="1" required>
            <input type="text" class="otp-input form-control text-center" maxlength="1" required>
            <input type="text" class="otp-input form-control text-center" maxlength="1" required>
            <input type="text" class="otp-input form-control text-center" maxlength="1" required>
          </div>
          <input type="hidden" name="otp" id="fullOtp">
          <input type="hidden" name="npk" value="<?php echo $_SESSION['username']; ?>">
          <div class="modal-footer">
            <button type="button" id="resendOtpBtn" class="btn btn-warning" disabled>Resend OTP</button>
            <button type="submit" class="btn btn-primary">Verifikasi OTP</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
    otpModal.show();

    // Event listener untuk tombol close (X)
    document.getElementById('closeModalBtn').addEventListener('click', function() {
        window.location.href = '../login_page.php'; // Redirect ke login_page.php
    });

    // OTP Input Handling
    const inputs = document.querySelectorAll('.otp-input');
    const fullOtp = document.getElementById('fullOtp');
    
    function handleInput(e, index) {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
        updateOtpValue();
    }

    function handleKeydown(e, index) {
        if (e.key === 'Backspace' && index > 0 && e.target.value === '') {
            inputs[index - 1].focus();
        }
    }

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => handleInput(e, index));
        input.addEventListener('keydown', (e) => handleKeydown(e, index));
    });

    function updateOtpValue() {
        fullOtp.value = Array.from(inputs).map(input => input.value).join('');
    }

    // Timer Logic
    let timeLeft = 300; // 5 menit dalam detik
    let timerInterval;
    const timerElement = document.getElementById("timer");
    const resendOtpBtn = document.getElementById('resendOtpBtn');

    function startTimer() {
        timerInterval = setInterval(() => {
            timeLeft--;
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `Kode OTP akan kadaluarsa dalam ${minutes}:${seconds.toString().padStart(2, '0')}`;

            // Aktifkan tombol Resend OTP saat waktu habis
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerElement.textContent = "Kode OTP telah kadaluarsa!";
                resendOtpBtn.disabled = false; // Aktifkan tombol Resend OTP
            }
        }, 1000);
    }
    startTimer();

    // Resend OTP Handler
    resendOtpBtn.addEventListener('click', function() {
        this.disabled = true; // Nonaktifkan tombol Resend OTP saat diklik
        
        fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `resendOtp=true&npk=<?php echo $_SESSION['username']; ?>`
        })
        .then(response => response.text())
        .then(data => {
            if(data === 'OTP_Terkirim') {
                alert('OTP baru telah dikirim!');
                // Reset timer
                timeLeft = 300;
                clearInterval(timerInterval);
                startTimer();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.disabled = false; // Aktifkan kembali tombol Resend OTP jika terjadi error
        });
    });
});
</script>

<style>
.otp-input {
    width: 40px;
    height: 50px;
    font-size: 24px;
    text-align: center;
    border: 2px solid #ced4da;
    border-radius: 5px;
}

#resendOtpBtn {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    transition: all 0.3s ease;
}

#resendOtpBtn:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    transform: scale(1.05);
}

#resendOtpBtn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
</style>