<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chiffrement et Déchiffrement AES avec PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Chiffrement et Déchiffrement AES-256 avec PHP</h2>
        <form action="index.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="message" class="form-label">Message à chiffrer ou déchiffrer</label>
                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                <div class="invalid-feedback">
                    Veuillez entrer un message.
                </div>
            </div>
            <div class="mb-3">
                <label for="key" class="form-label">Clé de chiffrement/déchiffrement (32 caractères)</label>
                <input type="text" class="form-control" id="key" name="key" maxlength="32" required>
                <div class="invalid-feedback">
                    Veuillez entrer une clé de 32 caractères.
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Action</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="action" id="encrypt" value="encrypt" checked>
                    <label class="form-check-label" for="encrypt">Chiffrer</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="action" id="decrypt" value="decrypt">
                    <label class="form-check-label" for="decrypt">Déchiffrer</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Exécuter</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $message = $_POST['message'];
            $key = $_POST['key'];
            $action = $_POST['action'];

            // Vérifier que la clé a exactement 32 caractères
            if (strlen($key) === 32) {

                // Fonction de chiffrement AES-256
                function encryptData($data, $key) {
                    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
                    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
                    return base64_encode($iv . $encrypted);
                }

                // Fonction de déchiffrement AES-256
                function decryptData($encryptedData, $key) {
                    $data = base64_decode($encryptedData);
                    $ivLength = openssl_cipher_iv_length('aes-256-cbc');
                    $iv = substr($data, 0, $ivLength);
                    $encrypted = substr($data, $ivLength);
                    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
                }

                if ($action == 'encrypt') {
                    // Chiffrement
                    $result = encryptData($message, $key);
                    echo '<div class="alert alert-success mt-4">Message chiffré : ' . htmlspecialchars($result) . '</div>';
                } elseif ($action == 'decrypt') {
                    // Déchiffrement
                    $result = decryptData($message, $key);
                    if ($result !== false) {
                        echo '<div class="alert alert-success mt-4">Message déchiffré : ' . htmlspecialchars($result) . '</div>';
                    } else {
                        echo '<div class="alert alert-danger mt-4">Échec du déchiffrement. Assurez-vous que le message chiffré et la clé sont corrects.</div>';
                    }
                }
            } else {
                echo '<div class="alert alert-danger mt-4">La clé doit contenir exactement 32 caractères.</div>';
            }
        }
        ?>
    </div>

    <script>
        // Script Bootstrap pour activer la validation des formulaires
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
