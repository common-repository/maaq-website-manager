<?php

use chillerlan\QRCode\{QRCode};

// Function to generate a QR code image and return its data.
function maaq__generate_qr_code($data)
{
    try {
        // Create a QRCode instance.
        $qrcode = new QRCode();
        // Generate the QR code image.
        $imageData = $qrcode->render($data);
        // Return the image data
        return $imageData;
    } catch (\Exception $e) {
        // Handle any exceptions that occur during QR code generation.
        return ''; // Return an empty string to indicate failure.
    }
}
